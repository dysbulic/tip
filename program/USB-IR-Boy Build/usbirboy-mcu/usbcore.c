/**
 * usbcore.c  --  USB-IR-Boy MCU USB firmware.
 *
 * www.sourceforge.net/projects/usbirboy/
 *
 * Copyright (c) 2004 Ilkka Urtamo
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 */

/*
  Changelog
  v0.2 2005-02-12 : fixed cat /proc/.../devices problem while usbirboy driver is loaded
  v0.2.1 2005-04-27 (AT):
    - fixed a bug that caused the device to leave configured state each time it received any setup packet
    - fixed register in dataout_endp2
    - added SELF_POWERED option
*/

#include "mc68hc908jb8.h"
#include "usbcore.h"
#include "types.h"

#ifdef SDCC
#pragma save
#pragma disable_warning 116  // disable SWAPENDIAN left shift warning
#endif

/* internal definitions */

#define SEND_BUFF_MASK (SEND_BUFF_LEN - 1)
#define RECEIVE_BUFF_MASK (RECEIVE_BUFF_LEN - 1)
#define HI_BYTE 0xff00
#define LO_BYTE 0x00ff
#define INC_SINDEX(a) do { (a) += 1; (a) &= SEND_BUFF_MASK; } while(0)
#define INC_RINDEX(a) do { (a) += 1; (a) &= RECEIVE_BUFF_MASK; } while(0)

#ifdef SDCC
#define SWAPENDIAN(a) (((((uint16)a)&(uint16)LO_BYTE)<<8) | ((((uint16)a)&(uint16)HI_BYTE)>>8))
#else
#define SWAPENDIAN(a) ((((a)&LO_BYTE)<<8) | (((a)&HI_BYTE)>>8))
#endif

/* usb standard device requests*/
#define REQ_GETSTATUS		0
#define REQ_CLEARFEATURE	1
#define REQ_SETFEATURE		3
#define REQ_SETADDRESS		5
#define REQ_GETDESCRIPTOR	6
#define REQ_SETDESCRIPTOR	7
#define REQ_GETCONFIGURATION	8
#define REQ_SETCONFIGURATION	9
#define REQ_GETINTERFACE	10
#define REQ_SETINTERFACE	11
#define REQ_SYNCHFRAME		12

#define REQ_MASK_TRANSDIRECTION 0x80
#define REQ_MASK_TYPE		0x60
#define REQ_MASK_RECIPIENT	0x18

#define REQ_DIRECT_HOSTTODEV	0x00
#define REQ_DIRECT_DEVTOHOST	0x80

#define REQ_TYPE_STANDARD	0x00
#define REQ_TYPE_CLASS		0x20
#define REQ_TYPE_VENDOR		0x40
#define REQ_TYPE_RESERVERD	0x60

#define REQ_RECIP_DEVICE	0x00
#define REQ_RECIP_INTERFACE	0x01
#define REQ_RECIP_ENDPOINT	0x02
#define REQ_RECIP_OTHER		0x03

/*standard device,interface and endpoint requests */
#define STDDEVREQ_GET_STATUS	0
#define STDDEVREQ_CLEAR_FEATURE	1
#define STDDEVREQ_SET_FEATURE	3
#define STDDEVREQ_SET_ADDR	5
#define STDDEVREQ_GET_DESCR	6
#define STDDEVREQ_SET_DESCR	7
#define STDDEVREQ_GET_CONFIG	8
#define STDDEVREQ_SET_CONFIG	9

#define STDINTFREQ_GET_STATUS		0
#define STDINTFREQ_CLEAR_FEATURE	1
#define STDINTFREQ_SET_FEATURE		3
#define STDINTFREQ_GET_INTERF		10
#define STDINTFREQ_SET_INTERF		11

#define STDENDPREQ_GET_STATUS		0
#define STDENDPREQ_CLEAR_FEATURE	1
#define STDENDPREQ_SET_FEATURE		3
#define STDENDPREQ_SYNCH_FRAME		12

#define STDENDPREQ_LANG			0
#define STDENDPREQ_PRODUCT		1
#define STDENDPREQ_MANUF		2

#define DESCR_TYPE_DEVICE		1
#define DESCR_TYPE_CONFIGURATION	2
#define DESCR_TYPE_STRING		3
#define DESCR_TYPE_INTERFACE		4
#define DESCR_TYPE_ENDPOINT		5

#define REQ_TYPE_DIRECT		0x80
#define REQ_TYPE_TYPE		0x60
#define REQ_TYPE_RECEPIENT	0x0F

/* configuration descriptor attributes */
#define ATTR_SELF_POWERED  (1<<6)
#define ATTR_REMOTE_WAKEUP (1<<5)

/* usb standard setup structures */

typedef struct setup_reguest {
  uint8 bmReqType;
  uint8 bReq;
  uint16 wValue;
  uint16 wIndex;
  uint16 wLength;
} SETUP_REQ;

/* usb standard descriptors*/

typedef struct device_descr {
  uint8 bLength;
  uint8 bDescriptorType;
  uint16 bcdUSB;
  uint8 bDeviceClass;
  uint8 bDeviceSubClass;
  uint8 bDeviceProtocol;
  uint8 bMaxPacketSize;
  uint16 idVendor;
  uint16 idProduct;
  uint16 bcdDevice;
  uint8 iManufacturer;
  uint8 iProduct;
  uint8 iSerialNumber;
  uint8 bNumConfigurations;
} DEVICE_DESCR;

typedef struct config_descr {
  uint8 bLength;
  uint8 bDescriptorType;
  uint16 wTotalLength;
  uint8 bNumInterfaces;
  uint8 bConfigurationVal;
  uint8 iConfiguration;
  uint8 bmAttributes;
  uint8 MaxPower;
} CONFIG_DESCR;

typedef struct interface_descr {
  uint8 bLength;
  uint8 bDescriptorType;
  uint8 bInterfaceNumber;
  uint8 bAlternateSetting;
  uint8 bNumEndPoints;
  uint8 bInterfaceClass;
  uint8 bInterfaceSubClass;
  uint8 bInterfaceProtocol;
  uint8 iInterface;
} INTERFACE_DESCR;

typedef struct endpoint_descr {
  uint8 bLength;
  uint8 bDescriptorType;
  uint8 bEndpointAddress;
  uint8 bmAttributes;
  uint16 wMaxPacketSize;
  uint8 bInterval;
} ENDPOINT_DESCR;

typedef struct full_config {
  CONFIG_DESCR Config;
  INTERFACE_DESCR Interface;
  ENDPOINT_DESCR endpoint1;
  ENDPOINT_DESCR endpoint2;
} FULL_CONFIG;

/* our values, refer to USB standard for details */

typedef struct devstring_descr {
  uint8 bLength;
  uint8 bDescriptorType;
  char  bString[12];       /*to fit "IR Boy" in unicode*/
} DEVSTRING_DESCR;

typedef struct manufstring_descr {
  uint8 bLength;
  uint8 bDescriptorType;
  char  bString[20];
} MANUFSTRING_DESCR;


typedef struct lang_descr {
  uint8 bLength;
  uint8 bDescriptorType;
  uint16 wLANGID0;
} LANG_DESCR;

const DEVICE_DESCR DeviceDescriptor = {
  sizeof(DEVICE_DESCR),
  DESCR_TYPE_DEVICE,
  SWAPENDIAN(0x0110),
  0,
  0,
  0,
  8,
  SWAPENDIAN(VENDOR_ID),
  SWAPENDIAN(PRODUCT_ID),
  SWAPENDIAN(PRODUCT_VER),
  STDENDPREQ_MANUF,
  STDENDPREQ_PRODUCT,
  0,
  1
};

const INTERFACE_DESCR InterfaceDescriptor = {
  sizeof(INTERFACE_DESCR),
  DESCR_TYPE_INTERFACE,
  0,
  0,
  2,
  0xFF,
  0x01,
  0xFF,
  0
};

const FULL_CONFIG ConfigurationDescriptor = {
  {
    sizeof(CONFIG_DESCR),
    DESCR_TYPE_CONFIGURATION,
    SWAPENDIAN(sizeof(FULL_CONFIG)),
    1,
    1,
    0,
#if SELF_POWERED
    0x80 | ATTR_SELF_POWERED,
    0
#else
    0x80,
    100 /* in units of 2 mA */
	/* pretend we take more than 100 mA to ensure 4.75 V */
#endif
  },
  {
    sizeof(INTERFACE_DESCR),
    DESCR_TYPE_INTERFACE,
    0,
    0,
    2,
    0xFF,
    0x01,
    0xFF,
    0
  },
  {
    sizeof(ENDPOINT_DESCR),
    DESCR_TYPE_ENDPOINT,
    0x81,
    0x03,
    SWAPENDIAN(0x0008),
    0x01
  },
  {
    sizeof(ENDPOINT_DESCR),
    DESCR_TYPE_ENDPOINT,
    0x02,
    0x03,
    SWAPENDIAN(0x0008),
    0x01
  }
};


const LANG_DESCR LangDescriptor = {
  sizeof(LANG_DESCR),
  DESCR_TYPE_STRING,
  SWAPENDIAN(0x0409)       /* US English */
};

const DEVSTRING_DESCR DevStringDescriptor = {
  sizeof(DEVSTRING_DESCR),
  DESCR_TYPE_STRING,
  "I\0R\0 \0B\0o\0y\0"              /* "IR Boy" in UTF-16 */
};

const MANUFSTRING_DESCR ManufStringDescriptor = {
  sizeof(MANUFSTRING_DESCR),
  DESCR_TYPE_STRING,
  "I\0 \0M\0a\0d\0e\0 \0I\0t\0!\0"  /* "I Made It!" in UTF-16 */
};

/* internals */
void dataout_endp2(void);
void datain_endp1(void);
void receive_data(void);
void datain_endp0(void);
void dataout_endp0(void);
void raw_send(uint8* data_address,uint8 slen);
void send_data(void);
void send_config(void);
void parse_setup_packet(void);
void parse_std_setup(void);
void parse_device_setup(void);
void parse_descriptor(void);
void stall(void);

uint8 sendbuffer[SEND_BUFF_LEN];
uint8 receivebuffer[RECEIVE_BUFF_LEN];
uint8* descrbuffer;
SETUP_REQ *pSetupPacket;
uint8 *mcu_datap;

uint8 sbuff_tail,sbuff_head;
uint8 cbuff_tail,cbuff_head;
uint8 rbuff_tail,rbuff_head;
uint8 ucr;
uint8 len;
uint8 address;
uint8 dev_configured;
uint8 last_packet;
uint8 rx_toggle;

/*******************************************************************/

uint8 usb_isdata() {
  if(rbuff_tail != rbuff_head && dev_configured == TRUE)
    return TRUE;
  else
    return FALSE;
}

/* does not block, returns true if successful */
uint8 usb_getc(uint8 *c) {
  if(usb_isdata()) {
    *c = receivebuffer[rbuff_tail];
    INC_RINDEX(rbuff_tail);
    return TRUE;
  }
  return FALSE;
}

/* does not block, returns true if successful */
uint8 usb_putc(uint8 c) {
  uint8 new_head;
	
  if(dev_configured == FALSE)
    return FALSE;
		
  new_head = (sbuff_head+1) & SEND_BUFF_MASK;
  if (new_head != sbuff_tail) {
    sendbuffer[sbuff_head]=c;
    sbuff_head = new_head;
    return TRUE;
  }
  return FALSE;
}

void usb_init(void) {
  /* enable usb and clear address */
  R_UADDR = USBEN;
	
  /* enable interrutps */
  R_UIR0 = TXD2IE | RXD2IE | TXD1IE | TXD0IE | RXD0IE;
  R_UIR2 = 0xFF;
	
  /* reset incoming flags */
  R_UCR0 = 0x00;
  R_UCR1 = 0x00;
  R_UCR3 = ENABLE1 | ENABLE2 | PULLEN;
  R_UCR4 = 0x00;

  sbuff_tail = sbuff_head = rbuff_tail = rbuff_head = 0;
  cbuff_tail = cbuff_head = 0;
  address = rx_toggle = 0;
  last_packet = FALSE;
  dev_configured = FALSE;
}

void stall(void) {
  R_UCR3 |= OSTALL0 | ISTALL0;
}

void parse_descriptor(void) {
  switch((SWAPENDIAN(pSetupPacket->wValue) & HI_BYTE) >> 8) {
  case DESCR_TYPE_DEVICE:
    descrbuffer = (uint8*)&(DeviceDescriptor);
    cbuff_tail = 0;
    if(SWAPENDIAN(pSetupPacket->wLength) < DeviceDescriptor.bLength)
      cbuff_head =SWAPENDIAN(pSetupPacket->wLength);
    else
      cbuff_head = DeviceDescriptor.bLength;
    send_config();
    break;

  case DESCR_TYPE_CONFIGURATION:
    descrbuffer = (uint8*)&(ConfigurationDescriptor);
    cbuff_tail = 0;			
    if(SWAPENDIAN(pSetupPacket->wLength) < sizeof(FULL_CONFIG))
      cbuff_head = SWAPENDIAN(pSetupPacket->wLength);
    else
      cbuff_head = sizeof(FULL_CONFIG);
    send_config();
    break;

  case DESCR_TYPE_STRING:
    switch(SWAPENDIAN(pSetupPacket->wValue) & LO_BYTE) {
    case STDENDPREQ_LANG:
      descrbuffer = (uint8*)&(LangDescriptor);
      cbuff_tail = 0;				
      if(SWAPENDIAN(pSetupPacket->wLength) < sizeof(LANG_DESCR))
        cbuff_head = SWAPENDIAN(pSetupPacket->wLength);
      else
        cbuff_head = LangDescriptor.bLength;
      send_config();
      break;
    case STDENDPREQ_PRODUCT:
      descrbuffer = (uint8*)&(DevStringDescriptor);
      cbuff_tail = 0;
      if(SWAPENDIAN(pSetupPacket->wLength) < sizeof(DEVSTRING_DESCR))
        cbuff_head = SWAPENDIAN(pSetupPacket->wLength);
      else
        cbuff_head = DevStringDescriptor.bLength;
      send_config();
      break;
				
    case STDENDPREQ_MANUF:
      descrbuffer = (uint8*)&(ManufStringDescriptor);
      cbuff_tail = 0;
      if(SWAPENDIAN(pSetupPacket->wLength) < sizeof(MANUFSTRING_DESCR))
        cbuff_head = SWAPENDIAN(pSetupPacket->wLength);
      else
        cbuff_head = ManufStringDescriptor.bLength;
      send_config();
      break;
					
    default:
      stall();
      break;
    }
  }
}

void parse_device_setup(void) {
  switch(pSetupPacket->bReq) {
  case STDDEVREQ_SET_ADDR:
    address = (uint8)SWAPENDIAN(pSetupPacket->wValue);
    raw_send(ADD_UDATAEND0,0);
    break;
  case STDDEVREQ_GET_DESCR:
    parse_descriptor();
    break;
  case STDDEVREQ_GET_CONFIG:
    raw_send(ADD_UDATAEND0,0);
    break;
  case STDDEVREQ_SET_CONFIG:
    raw_send(ADD_UDATAEND0,0);
    switch(SWAPENDIAN(pSetupPacket->wValue)) {
    case 1:  /* we support only one config */
      R_UCR1 = TX1E;
      R_UCR2 = RX2E;
      sbuff_tail = sbuff_head = rbuff_tail = rbuff_head = 0;
      cbuff_tail = cbuff_head = 0;
      dev_configured = TRUE;
      rx_toggle = 0;
      break;
    case 0: /* if config is 0, we return to address state */
      R_UCR1 = 0x00;
      R_UCR2 = 0x00;
      dev_configured = FALSE;
      break;					
    default:
      stall();
      break;
    }			
    break;
  case STDDEVREQ_CLEAR_FEATURE:
  case STDDEVREQ_SET_FEATURE:
  case STDDEVREQ_SET_DESCR:
  default:
    stall();
    break;
  }
}

void parse_std_setup(void) {
  switch(pSetupPacket->bmReqType & REQ_MASK_RECIPIENT) {
  case REQ_RECIP_DEVICE:
    parse_device_setup();
    break;
  case REQ_RECIP_INTERFACE:
  case REQ_RECIP_ENDPOINT:
  case REQ_RECIP_OTHER:
  default:
    stall();
    break;
  }
}
	
void parse_setup_packet(void) {
  pSetupPacket=(SETUP_REQ*)ADD_UDATAEND0;

  switch(pSetupPacket->bmReqType & REQ_MASK_TYPE) {
  case REQ_TYPE_STANDARD:
    parse_std_setup();
    break;
  case REQ_TYPE_CLASS:
  case REQ_TYPE_VENDOR:
  case REQ_TYPE_RESERVERD:
  default:
    stall();
    break;
  }
}

void send_config(void) {
  len = 0;
  mcu_datap = ADD_UDATAEND0;

  while((cbuff_tail != cbuff_head) && len != 8) {
    (*mcu_datap) = descrbuffer[cbuff_tail];
    mcu_datap++;
    len++;
    cbuff_tail++;
  }
  if(cbuff_tail == cbuff_head)	// all data sent, force last packet
    last_packet = TRUE;
  raw_send(ADD_UDATAEND0,len);
}

void send_data(void) {
  len = 0;
  mcu_datap = ADD_UDATAEND1;

  while((sbuff_tail != sbuff_head) && len != 8) {
    (*mcu_datap) = sendbuffer[sbuff_tail];
    mcu_datap++;
    len++;
    INC_SINDEX(sbuff_tail);
  }
  raw_send(ADD_UDATAEND1,len);
}

void raw_send(uint8* data_address, uint8 slen) {
  /* send whatever is in send memory address */
  if(data_address == ADD_UDATAEND0) {
    ucr = (R_UCR0 & T0SEQ) | (TP0SIZ_MASK & slen) | TX0E | RX0E;
    ucr ^= T0SEQ;     /* toggle DATA1/0 */
    if(len == 0)      // this is just an ack, force last packet (if not done by send_config)
      last_packet = TRUE;
  }
	
  if(data_address == ADD_UDATAEND1) {
    ucr = (R_UCR1 & T1SEQ) | (TP1SIZ_MASK & slen) | TX1E;
    ucr ^= T1SEQ;     /* toggle DATA1/0 */
  }	
}


void dataout_endp0(void) {
  R_UCR0 &= ~RX0E;
  R_UCR0 &= ~TX0E;
  R_UIR2 = RXD0FR;

  /*check if we got setup packet*/
  if(R_USR0 & SETUP) {
    last_packet = FALSE; 		// force new setup to start
    // fixed: must not leave configured state here
    parse_setup_packet();
    ucr |= T0SEQ | RX0E | TX0E;
    R_UCR0 = ucr;
  } else {
    R_UCR0 = RX0E;
  }
}

void datain_endp0(void) {	
  R_UCR0 &= ~TX0E;
  R_UIR2 = TXD0FR;

  if(address != 0) {
    // set the address if we just got address from last OUT
    R_UADDR |= (UADD_MASK & address);
    address = 0;
  }

  // send more data if this is not ack for last packet
  if(last_packet == FALSE) {
    send_config();
    ucr |= TX0E;
    R_UCR0 = ucr;
  } else {
    // this was last packet, reset last packet status
    last_packet = FALSE;
  }
}

void receive_data(void) {
  mcu_datap = ADD_UDATAEND2;
  len = R_USR1 & TP2SIZ_MASK;
	
  for(len = 0; len < (R_USR1 & TP2SIZ_MASK); ++len) {
    receivebuffer[rbuff_head]= *(mcu_datap + len);
    INC_RINDEX(rbuff_head);
  }
}

void datain_endp1(void) {
  R_UCR1 &= ~TX1E;
  R_UIR2 = TXD1FR;

  send_data();

  R_UCR1 = ucr;
}

void dataout_endp2(void) {
  R_UCR2 &= ~RX2E;
  R_UIR2 = RXD2FR;

  /* if toggle matches, get data*/
  if(rx_toggle == (R_USR1 & R2SEQ))
    receive_data();
  else
    stall();

  /* prepare next data toggle*/
  rx_toggle ^= R2SEQ;
  R_UCR2 = RX2E;
}

/*usb interupt handler*/
#ifdef SDCC
void usb_inth(void) interrupt 2
#else
interrupt void usb_inth(void)
#endif
{
  if(R_UIR1 & RXD0F) // ep0 OUT packet received
    dataout_endp0();

  if(R_UIR1 & TXD0F) // ep0 IN packet received
    datain_endp0();

  if(R_UIR1 & TXD1F) // ep1 IN packet received
    datain_endp1();
  
  if(R_UIR1 & RXD2F) // ep2 OUT packet received
    dataout_endp2();

  if(R_UIR1 & RSTF) { // RESET received, reinitalize usb
    R_UIR2 = RSTFR;
    usb_init();
    R_UCR0 = RX0E;
  }
}

#ifdef SDCC
#pragma restore
#endif
