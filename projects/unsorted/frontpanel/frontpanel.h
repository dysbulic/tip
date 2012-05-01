#ifndef FRONTPANEL_H
#define FRONTPANEL_H
/**
 */

#include <linux/list.h>

MODULE_AUTHOR("Will Holcomb");
MODULE_SUPPORTED_DEVICE("Toshiba PMD-C00l USB Front Panel");
MODULE_DESCRIPTION("Reads from and writes to front panel");
MODULE_LICENSE("GPL");

#define NAME "frontpanel" /* default driver name */
#define DEV_PREFIX "dev"  /* preface for devices in devfs */
#define MAX_NAME_LEN 100  /* maximum length for the name */
#define MAX_DEVICES 100   /* maximum simultaneously connected devices */
#define MAX_RETRIES 100   /* maximum number of times to retry write */

/**
 * Subclasses are no longer used except whether a device
 * is available at boot or not
 */
#define NO_SUBCLASS 0
#define BOOT_SUBCLASS 1

/**
 * The protocol only has meaning if the device is available at
 * boot time. Otherwise it is not used.
 */
#define NO_PROTOCOL 0
#define KEYBOARD_PROTOCOL 1
#define MOUSE_PROTOCOL 2

/**
 * Redhat 8 has devrequest instead
 */
#if LINUX_VERSION_CODE <= KERNEL_VERSION(2,4,18)
struct usb_ctrlrequest {
  __u8 bRequestType;
  __u8 bRequest;
  __u16 wValue;
  __u16 wIndex;
  __u16 wLength;
};
#endif

/**
 * We need a local data structure, to be allocated for each new
 * device plugged in the USB bus
 */
struct frontpanel_context {
  char* name;                   /* holds name sent to probe */
  devfs_handle_t dev;           /* holds devfs device */
  struct urb* in_urb;           /* USB Request block, to get USB data*/
  struct urb* out_urb;          /* USB Request block, to send USB data*/
  int retry_count;              /* number of times a write has been skipped */
  __u8 out_endpoint;            /* the address of the bulk out endpoint */
  struct semaphore sem;         /* lock for this structure */
};

static void delete_context(struct frontpanel_context* context) {
  if (context != NULL) {
#ifdef CONFIG_DEVFS_FS
    devfs_unregister(context->dev);
#endif

    if (context->in_urb != NULL) {
      if (context->in_urb->transfer_buffer != NULL) {
	kfree(context->in_urb->transfer_buffer);
      }
      usb_unlink_urb(context->in_urb);
      usb_free_urb(context->in_urb);
    }
    if (context->out_urb != NULL) {
      if (context->out_urb->transfer_buffer != NULL) {
	kfree(context->out_urb->transfer_buffer);
      }
      if (context->out_urb->setup_packet != NULL) {
        kfree(context->out_urb->setup_packet);
      }
      usb_free_urb(context->out_urb);
    }
    kfree(context);
  }
}

struct frontpanel_context_list {
  struct list_head list;
  struct frontpanel_context context;
};

#endif
