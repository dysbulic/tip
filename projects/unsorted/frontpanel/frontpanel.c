/**
 * Copyright (C) 2001 Alessandro Rubini and Jonathan Corbet
 * Copyright (C) 2001 O'Reilly & Associates
 *
 * The source code in this file can be freely used, adapted,
 * and redistributed in source or binary form, so long as an
 * acknowledgment appears in derived source files.  The citation
 * should list that the code comes from the book "Linux Device
 * Drivers" by Alessandro Rubini and Jonathan Corbet, published
 * by O'Reilly & Associates.   No warranty is attached;
 * we cannot take responsibility for errors or fitness for use.
 *
 *
 * This driver is just a test to talk to a little usb front
 * panel that was once attached to a toshiba monitor (c. 1997)
 * model number PMD-C00l. Mainly I am just trying to learn
 * some about device drivers and usb.
 */

#include <linux/config.h>
#include <linux/kernel.h>
#include <linux/errno.h>
#include <linux/init.h>
#include <linux/slab.h> /* replaces malloc.h */
#include <linux/module.h>
#include <linux/kref.h>
#include <linux/smp_lock.h>
#include <linux/usb.h>
#include <asm/uaccess.h>

/*
#if LINUX_VERSION_CODE <= KERNEL_VERSION(2,3,0)
#  error "This module only works with 2.4 kernels"
#else
*/

#include <linux/fs.h>
#include <linux/devfs_fs_kernel.h>
#include <linux/string.h>

#include "frontpanel.h"

/* Disable usage counting for development */
#ifdef DEBUG
#  undef MOD_INC_USE_COUNT
#  undef MOD_DEC_USE_COUNT
#  define MOD_INC_USE_COUNT
#  define MOD_DEC_USE_COUNT
#endif

/* Name of the devfs directory */
char* devname = NAME;
MODULE_PARM (devname, "s");
MODULE_PARM_DESC (devname, "Name of the devfs directory (default = " NAME ")");

/* Major number assigned when using standard chrdev */
int majornum = 0;
MODULE_PARM (majornum, "i");
MODULE_PARM_DESC (majornum, "Major number used for character device (default = dynamic)");

/* the global usb devfs handle */
extern devfs_handle_t usb_devfs_handle;

/* Directory for creating devfs devices */
static devfs_handle_t dir = NULL;

/* Contexts associated with the order devices are plugged in */
struct list_head contexts;
LIST_HEAD(contexts);

/* For now assume only one... */
struct frontpanel_context* context = NULL;

/* File operations functions */
static loff_t frontpanel_seek(struct file* filp, loff_t pos, int whence);
static int frontpanel_open(struct inode* inode, struct file* filp);
static ssize_t frontpanel_write(struct file* filp, const char* buffer,
                                size_t length, loff_t* pos);
static int frontpanel_ioctl(struct inode* inode, struct file* file,
                            unsigned int cmd, unsigned long arg);

/**
 * File structure used to register as character device
 */
static struct file_operations frontpanel_fops = {
  .owner = THIS_MODULE,
  .open = frontpanel_open,
  .llseek = frontpanel_seek,
  .write = frontpanel_write,
  .ioctl = frontpanel_ioctl
};

/* USB operations functions */
static void frontpanel_irq(struct urb *urb);
static void frontpanel_control_callback(struct urb* urb);
static void* frontpanel_probe(struct usb_device *udev, unsigned int ifnum,
                              const struct usb_device_id *id);
static void frontpanel_disconnect(struct usb_device *udev,
                                  void *clientdata);

/**
 * The id_table, lists all devices that can be handled by this driver.
 * The three numbers are class, subclass, protocol. <linux/usb.h> has
 * more details about interface mathces and vendor/device matches.
 * This feature is not there in version 2.2.
 */
static struct usb_device_id frontpanel_id_table [] = {
  /* $ cat /proc/bus/usb/devices | grep -e "^I"
   * I: If#= 0 Alt= 0 #EPs= 1 Cls=09(hub  ) Sub=00 Prot=00 Driver=hub
   * USB_INTERFACE_INFO(Cls (mnemonic defined in usb.h), Sub, Proto)
   */
  {
    USB_INTERFACE_INFO(USB_CLASS_HID, NO_SUBCLASS, NO_PROTOCOL)
    /* .driver_info set dynaically in init */
  },
  {} /* no more matches */
};

/**
 * The callbacks are registered within the USB subsystem using the
 * usb_driver data structure
 */
static struct usb_driver frontpanel_usb_driver = {
  /* .name set dynaically in init */
  .probe = frontpanel_probe,
  .disconnect = frontpanel_disconnect,
  .id_table = frontpanel_id_table,
  .fops = &frontpanel_fops
};

/**
 * Registration
 */
static int __init frontpanel_init(void) {
  /* set names */
  frontpanel_id_table[0].driver_info =
    (unsigned long)(frontpanel_usb_driver.name = devname);

#ifdef CONFIG_DEVFS_FS
  dir = devfs_mk_dir(usb_devfs_handle, devname, NULL);
  dbg("%s: registered %s", NAME, devname);
#else
  majornum = register_chrdev(majornum, devname, &frontpanel_fops);
  dbg("%s: registered %s (%d)", NAME, devname, majornum);
#endif
  return usb_register(&frontpanel_usb_driver);
}

/**
 * Removal
 */
static void __exit frontpanel_exit(void) {
  dbg("%s: deregistering %s", NAME, devname);
#ifdef CONFIG_DEVFS_FS
  devfs_unregister(dir);
#else
  unregister_chrdev(majornum, devname);
#endif
  usb_deregister(&frontpanel_usb_driver);
}

module_init(frontpanel_init);
module_exit(frontpanel_exit);

static void print_bytes(const char* source,
			const unsigned char data[],
			int length) {
  int i;
  char* buffer = kmalloc(sizeof(char) * strnlen(source, MAX_NAME_LEN) +
                         3 * length + 10, GFP_KERNEL);
  char* out = buffer;
  out += sprintf(out, "%d byte%s from %s: ", length,
                 length != 1 ? "s" : "", source);
  for (i = 0; i < length; i++) {
    out += sprintf(out, "%02x", data[i]);
  }
  dbg("%s", buffer);
  kfree(buffer);
}

/**
 * Handler to choose the output address on the device
 */
static loff_t frontpanel_seek(struct file* filp, loff_t pos,
                              int whence) {
  /* whence = SEEK_SET | SEEK_CUR | SEEK_END */
  return (loff_t)-ENOSYS;
}

/**
 * Handler to choose the output address on the device
 */
static int frontpanel_open(struct inode* inode, struct file* filp) {
  int minor = MINOR(inode->i_rdev);
  /* If not using devfs this has to be set manually */
  if(!filp->private_data) {
    if(context == NULL) {
      return -ENODEV;
    } else {
      filp->private_data = context;
    }
  }
  dbg("%s: opening %s (%d)", NAME, devname, minor);
  return 0;
}

/**
 * Handler to allow data to be sent to the device via a device
 * file.
 */
static ssize_t frontpanel_write(struct file* filp, const char* buffer,
                                size_t length, loff_t* pos) {
  struct frontpanel_context* context =
    (struct frontpanel_context*)filp->private_data;
  ssize_t write_len;

  if (context->out_urb == NULL) {
    return -ENODEV;
  }

  /* either write a word or a byte */
  write_len = length > 1 ? 2 : 1;

  context->retry_count++;

  /* see if we are already in the middle of a write */
  if (context->out_urb->status == -EINPROGRESS) {
    write_len = 0;
    goto exit;
  }

  print_bytes("dev", buffer, write_len);

  {
    struct usb_ctrlrequest* controlreq = 
      (struct usb_ctrlrequest*)context->out_urb->setup_packet;
    controlreq->wValue = 0;
    if (copy_from_user(&controlreq->wValue, buffer, write_len)) {
      return -EFAULT;
    } else {
      dbg("%s: %d byte%s written to device: %d attempt%s", NAME,
	  write_len, write_len != 1 ? "s" : "",
	  context->retry_count, context->retry_count != 1 ? "s" : "");
      context->retry_count = 0;
    }
  }

  {
    int retval = usb_submit_urb(context->out_urb);
    if (retval != 0) {
      err("%s: bad return value on write submit: %d", NAME, retval);
      return retval;
    }
  }

 exit:
  if(context->retry_count > MAX_RETRIES) {
    err("%s: more than %d retries on urb", NAME, MAX_RETRIES);
    return -EFAULT;
  } 
  return write_len;
}

/**
 * Handler for miscelaneous options
 */
static int frontpanel_ioctl(struct inode* inode, struct file* file,
                            unsigned int cmd, unsigned long arg) {
  return -ENOSYS;
}

/**
 * Create the character device to allow writing to the frontpanel
 */
static void register_dev(struct frontpanel_context* context) {
#ifdef CONFIG_DEVFS_FS
  char devname[strnlen(devname, MAX_NAME_LEN) + MAX_DEVICES / 10 + 1];
  sprintf(devname, "%s%i", DEV_PREFIX, 0); /* replace with free # */
  if (dir == NULL) {
    err("%s: no devfs directory to place %s in", NAME, devname);
  } else {
    context->dev =
      devfs_register(dir, devname,
                     DEVFS_FL_DEFAULT | DEVFS_FL_AUTO_DEVNUM,
                     0, 0, /* unused major and minor */
                     S_IFCHR | S_IRUGO | S_IWUSR, /* char device a+r,u+w */
                     &frontpanel_fops, context);
  }
#else
  if(majornum <= 0) {
    err("%s: no major device to create device %s", NAME, devname);
  } else {
  }
#endif
}

/**
 * Handler for data sent in by the device. The function is called by
 * the USB kernel subsystem whenever a device spits out new data
 */
static void frontpanel_irq(struct urb *urb) {
  if (urb->status == USB_ST_NOERROR) {
    print_bytes("usb", (unsigned char*)urb->transfer_buffer,
		urb->actual_length);
  }
}

/**
 * callback used after control urb is sent
 */
static void frontpanel_control_callback(struct urb* urb) {
  struct frontpanel_context* context =
    (struct frontpanel_context*)urb->context;

  if (urb->status != 0) {
    err("%s: bad return on %s control urb: %d",
	NAME, context->name, urb->status);
  }
}

/*
 * These two callbacks are invoked when an USB device is detached
 * or attached to the bus
 */
static void* frontpanel_probe(struct usb_device *udev,
                              unsigned int ifnum,
                              const struct usb_device_id* id) {
  /* The probe procedure is pretty standard. Device matching
   * has already been performed based on the id_table structure
   */
  struct usb_interface* interface =
    &udev->actconfig->interface[ifnum]; /* actconfig = active configuration */
  struct usb_interface_descriptor* interface_desc =
    &interface->altsetting[interface->act_altsetting];
  struct usb_endpoint_descriptor* endpoint;
  struct usb_ctrlrequest* controlreq;

  int i;

  /* Use global temporarily
   struct frontpanel_context *context;
  */
  
  {
    size_t max_len = 63;
    char* manufacturer = kmalloc(sizeof(char) * max_len, GFP_KERNEL);
    char* product = kmalloc(sizeof(char) * max_len, GFP_KERNEL);

    if (manufacturer != NULL && product != NULL) {
      usb_string(udev, udev->descriptor.iManufacturer,
		 manufacturer, max_len);
      usb_string(udev, udev->descriptor.iProduct,
		 product, max_len);
  
      dbg("%s: probe called for %s device [%s (0x%04X) - %s (0x%04X)]",
	  NAME, (char*)id->driver_info,
	  manufacturer, udev->descriptor.idVendor,
	  product, udev->descriptor.idProduct);
    } else {
      err("%s: couldn't allocate memory for device info", NAME);
    }
    if (manufacturer != NULL) kfree(manufacturer);
    if (product != NULL) kfree(product);
  }
  
  /* allocate and zero a new data structure for the new device */
  context = kmalloc(sizeof(struct frontpanel_context), GFP_KERNEL);
  if (context == NULL) {
    err("%s: could not allocate space for context", NAME);
    return NULL;
  }
  memset(context, (int)NULL, sizeof(*context));
  context->name = (char*)id->driver_info;
  init_MUTEX(&context->sem);
  
  dbg("%s: checking %d endpoint%s on 1 of %d interface%s"
      " on 1 of %d configuration%s",
      NAME, interface_desc->bNumEndpoints,
      interface_desc->bNumEndpoints != 1 ? "s" : "",
      udev->actconfig->bNumInterfaces,
      udev->actconfig->bNumInterfaces != 1 ? "s" : "",
      udev->descriptor.bNumConfigurations,
      udev->descriptor.bNumConfigurations != 1 ? "s" : "");

  for (i = 0; i < interface_desc->bNumEndpoints; i++) {
    endpoint = &interface_desc->endpoint[i];
    if (((endpoint->bEndpointAddress & USB_ENDPOINT_DIR_MASK) ==
	 USB_DIR_IN) &&
        ((endpoint->bmAttributes & USB_ENDPOINT_XFERTYPE_MASK) ==
	 USB_ENDPOINT_XFER_INT)) {
      int pipe = usb_rcvintpipe(udev, endpoint->bEndpointAddress);
      
      context->in_urb = usb_alloc_urb(0);
      if (context->in_urb == NULL) {
        err("%s: could not create input urb", NAME);
        goto error;
      }

      usb_fill_int_urb(context->in_urb, udev, pipe,
		       NULL, usb_maxpacket(udev, pipe, usb_pipeout(pipe)),
		       frontpanel_irq, context, endpoint->bInterval);

      dbg("%s: max incoming packet size: %d",
	  NAME, context->in_urb->transfer_buffer_length);

      context->out_endpoint = endpoint->bEndpointAddress;

      context->in_urb->transfer_buffer = kmalloc
	(sizeof(__u8) * context->in_urb->transfer_buffer_length,
	 GFP_KERNEL);
      if (context->in_urb->transfer_buffer == NULL) {
        err("%s: could not create input buffer (%d)",
	    NAME, context->in_urb->transfer_buffer_length);
        goto error;
      }

      if (usb_submit_urb(context->in_urb)) {
        err("%s: could not submit input urb", NAME);
        goto error;
      } 
    } else {
      dbg("Unrecognized endpoint: address = 0x%02X (0x%02x);"
	  " attributes = 0x%02X (0x%02X)",
	  endpoint->bEndpointAddress,
	  endpoint->bEndpointAddress & USB_ENDPOINT_DIR_MASK,
	  endpoint->bmAttributes,
	  endpoint->bmAttributes & USB_ENDPOINT_XFERTYPE_MASK);
    }
  }

  /* int usb_set_protocol(struct usb_device *dev, int ifnum, int protocol)
   * hid specific
   */
  /* usb_set_protocol(udev, interface_desc->bInterfaceNumber, 0); */

  /* int usb_set_idle(struct usb_device *dev, int ifnum, int duration,
   *  int report_id)
   * hid specific
   */
  /* usb_set_idle(udev, interface_desc->bInterfaceNumber, 0, 0); */
  
  context->out_urb = usb_alloc_urb(0);
  
  controlreq = kmalloc(sizeof(struct usb_ctrlrequest), GFP_KERNEL);
  if (controlreq == NULL) {
    err("%s: could not allocate control request", NAME);
    goto error;
  }

  controlreq->bRequestType =
    /* USB_DIR_OUT | USB_TYPE_STANDARD | USB_RECIP_DEVICE; */
    USB_DIR_OUT | USB_TYPE_STANDARD | USB_RECIP_INTERFACE;
  controlreq->bRequest = USB_REQ_SET_CONFIGURATION;
  /* controlreq->bRequest = USB_REQ_SET_REPORT; */
  controlreq->wValue = 0;
  controlreq->wIndex = interface_desc->bInterfaceNumber;
  controlreq->wLength = 0;

  usb_fill_control_urb(context->out_urb,  /* pointer to urb to initialize */
		       udev,              /* pointer to device */
		       usb_sndctrlpipe(udev, 0), /* endpoint pipe */
		       (void*)controlreq, /* pointer to setup packet */
		       NULL,              /* pointer to transfer buffer */
		       0,                 /* transfer buffer length */
		       frontpanel_control_callback, /* callback */
		       context);          /* private data */

  if (context->out_urb->transfer_buffer_length > 0) {
    context->out_urb->transfer_buffer = kmalloc
      (sizeof(__u8) * context->out_urb->transfer_buffer_length, GFP_KERNEL);
    if (context->out_urb->transfer_buffer == NULL) {
      err("%s: could not create output buffer (%d)",
	  NAME, context->out_urb->transfer_buffer_length);
      goto error;
    }
  }

  /* create character device for writing */
  register_dev(context);

  /* announce yourself */
  info("%s: probe successful for %s (max [in/out] = [%d/%d])",
       NAME, context->name,
       context->in_urb != NULL ? context->in_urb->transfer_buffer_length : -1,
       (context->out_urb != NULL
	? context->out_urb->transfer_buffer_length : -1));
  
  /* If you use this then the device will have to be disconnected
   * before the module can be unloaded
   */
  MOD_INC_USE_COUNT;
  goto exit;

 error:
  delete_context(context);
  context = NULL;

 exit:  
  /* and return the new structure */
  return context;
}

static void frontpanel_disconnect(struct usb_device *udev,
                                  void *clientdata) {
  /* the clientdata is the sample_device we passed originally */
  struct frontpanel_context *context =
    (struct frontpanel_context *)clientdata;
  
  dbg("%s: %s disconnected", NAME, context->name);

  delete_context(context);

  /* Only do this if the use count was incremented in frontpanel_probe
   */
  MOD_DEC_USE_COUNT

  return;
}

/*#endif*/ /* < 2.4 */
