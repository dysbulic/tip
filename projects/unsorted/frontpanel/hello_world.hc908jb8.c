/**
 * MC68HC908JB8 USB Hello World
 * 
 * This is a simple test program for the Freescale (formerly Motorola)
 * MC68HC908JB8. It brings the USB interface into the configured
 * state.
 * 
 * \author: Will Holcomb <wholcomb@gmail.com>
 * \date: August 2006
 * \compiler: Small Device C Compiler (http://sdcc.sourceforge.net)
 *
 */

#include "types.h"
#include "usb.h"
#include "mc68hc908jb8.h"

#define DEVICE_STRING "H\0e\0l\0l\0o\0 U\0S\0B\0 \0W\0o\0r\0l\0d\0" // UTF-16
#define DEVICE_STRING_LEN 30
#define MANUFACTURER "W\0i\l\0l\0 \0H\0o\0l\0c\0o\0m\0b\0" // UTF-16
#define MANUFACTURER_LEN 24

#define SEND_BUFF_LEN	   128	/** in bytes, intervals of 8 (max 256 == uint8) */
#define RECEIVE_BUFF_LEN   16	/** in bytes, intervals of 8 (max 256 == uint8) */

#define VENDOR_ID           0xFFFE   /* manufacturer id (chosen at random from the end of the list) */
#define PRODUCT_ID	    0x0000   /* product id */
#define PRODUCT_VERSION	    0x0001   /* product version */

#define SELF_POWERED	    0        /* set to 0 if using USB power, 1 otherwise */

#define LANGUAGE_INDEX      0
#define MANUFACTURER_INDEX  1
#define PRODUCT_INDEX       2

#ifdef SDCC
//#pragma save
#pragma disable_warning 116  // disable warning 116: left shifting more than size of object changed to zero
#endif

DEVSTRING_DESCRIPTOR(DEVICE_STRING_LEN);
MANUFACTURER_DESCRIPTOR(MANUFACTURER_LEN);

typedef struct descriptor { uint8 bLength; } DESCRIPTOR;

const DEVICE_DESCRIPTOR DeviceDescriptor = {
  sizeof(DEVICE_DESCRIPTOR), // bLength
  DESCR_TYPE_DEVICE,         // bDescriptorType
  htons(USB_V_1_1),          // bcdUSB
  CLASS_INDEPENDENT,         // bDeviceClass
  SUBCLASS_NONE,             // bDeviceSubClass
  CLASS_INDEPENDENT,         // bDeviceProtocol
  MAX_PACKET_SIZE,           // bMaxPacketSize
  htons(VENDOR_ID),          // idVendor
  htons(PRODUCT_ID),         // idProduct
  htons(PRODUCT_VERSION),    // bcdDevice
  MANUFACTURER_INDEX,        // iManufacturer
  PRODUCT_INDEX,             // iProduct
  0,                         // iSerialNumber
  0                          // bNumConfigurations
};

const LANG_DESCRIPTOR LangDescriptor = {
  sizeof(LANG_DESCRIPTOR),
  DESCR_TYPE_STRING,
  htons(LANG_US_EN)
};

const DEVSTRING_DESCRIPTOR DevStringDescriptor = {
  sizeof(DEVSTRING_DESCRIPTOR),
  DESCR_TYPE_STRING,
  DEVICE_STRING
};

const MANUFACTURER_DESCRIPTOR ManufStringDescriptor = {
  sizeof(MANUFACTURER_DESCRIPTOR),
  DESCR_TYPE_STRING,
  MANUFACTURER
};

uint8 ucr;
USB_REQUEST* pCurrentRequest;
DESCRIPTOR* pCurrentDescriptor;
uint8 sentBytes = 0;
uint8 totalBytes = 0;
uint8 boolLastPacket = TRUE;
uint8 boolConfigured = FALSE;

/**
 * Resets the registers associated with the operation of the USB subsystem and
 * raises the pull-up voltage, preparing the device to be connected.
 */
void reset_usb() {
  UIR0 = TXD0IE | RXD0IE; /* enable interrutps on endpoint 0 */
  UIR2 = 0xFF;            /* reset all flags */

  UCR0 = 0x00;
  UCR1 = 0x00;
  UCR2 = 0x00;
  UCR3 = PULLEN;          /* enable pull-up voltage on D+ to start suspend process */
  UCR4 = 0x00;
}

/**
 * Sets the variables associated with the state machine driving the program to
 * their initial state values.
 */
void reset_vars() {
  sentBytes = totalBytes = 0;
  boolConfigured = FALSE;
  boolLastPacket = TRUE;
  //rx_toggle = 0;
}

/**
 * Resets both the USB and state variables
 */
void reset_system() {
  reset_usb();
  reset_vars();
}

void stall() {
  UCR3 |= OSTALL0 | ISTALL0;
}

void datain(uint8 endpoint);
void dataout(uint8 endpoint);
void handle_control_transfer();
void handle_standard_request(); 
void handle_device_request();
void send_descriptor();
void send_config();
void raw_send(uint8* pDataAddress, uint8 sLen);
#define send_ack(pDataAddress) raw_send(pDataAddress, 0)

void usb_interrupt_handler()
#ifdef SDCC
 interrupt 2
#endif
{
  /* All USB devices are required to support a message pipe on
   * endpoint 0 as the "Default Control Pipe." This pipe uses
   * Interrupt Transfers (IN and OUT) to communicate configuration
   * information to the host.
   *
   * This program does not support any real communication, so only
   * endpoint 0 is activated.
   */
  if(UIR1 & RXD0F) dataout(0); // Endpoint 0 OUT packet received
  if(UIR1 & TXD0F) datain(0);  // Endpoint 0 IN packet received

  if(UIR1 & RSTF) {            // RESET packet recieved
    reset_system();
    UCR0 = RX0E;               // Enable recieve on endpoint 0
  }
}

void dataout(uint8 endpoint) {
  switch(endpoint) {
  case 0:
    UCR0 &= ~RX0E & ~TX0E;                    // Disable endpoint 0 transmit and recieve
    UIR2 = RXD0FR;                            // Reset endpoint 0 recieve flag

    if(USR0 & SETUP) {                        // SETUP packet
      pCurrentRequest = (USB_REQUEST*)UE0D0;
      handle_control_transfer();
      UCR0 = ucr | T0SEQ | TX0E;              // T0SEQ? and enable transmit
    }
    UCR0 |= RX0E;                             // Enable recieve on endpoint 0
    break;
  case 1:                                     // Endpoint 1 does not recieve
  case 2:                                     // Endpoint 2 not used
  }
}

void datain(uint8 endpoint) {
  switch(endpoint) {
  case 0:
    UCR0 &= ~TX0E;                 // Disable endpoint 0 transmit
    UIR2 = TXD0FR;                 // Reset endpoint 0 transmit flag

    if(boolLastPacket == FALSE) {  // Send more data if this is not ack for last packet
      send_config();
      ucr |= TX0E;                 // Enable transmit on endpoint 0
      UCR0 = ucr;
    } else {                       // This was last packet, reset last packet status
      boolLastPacket = FALSE;
    }
  }
}

void handle_control_transfer() {
  switch(pCurrentRequest->bmRequestType & REQ_MASK_TYPE) {
  case REQ_TYPE_STANDARD:
    handle_standard_request();
    break;
  case REQ_TYPE_CLASS:
  case REQ_TYPE_VENDOR:
  case REQ_TYPE_RESERVERD:
  default:
    stall();
  }
}

void handle_standard_request() {
  switch(pCurrentRequest->bmRequestType & REQ_MASK_RECIPIENT) {
  case REQ_RECIP_DEVICE:
    handle_device_request();
    break;
  case REQ_RECIP_INTERFACE:
  case REQ_RECIP_ENDPOINT:
  case REQ_RECIP_OTHER:
  default:
    stall();
  }
}

void handle_device_request() {
  switch(pCurrentRequest->bRequest) {
  case REQ_SET_ADDRESS:
    {
    uint8 deviceAddress = (uint8)ntohs(pCurrentRequest->wValue);
    UADDR &= ~UADD_MASK;                                          // Clear the existing address
    UADDR |= (UADD_MASK & deviceAddress);                         // Set the address from the host

    // !! I'm not sure if the ACK is set here or on the reciept of an IN packet
    send_ack(UE0D0);
    }
    break;
  case REQ_GET_DESCRIPTOR:
    send_descriptor();
    break;
  case REQ_GET_CONFIGURATION:
    send_ack(UE0D0);
    break;
  case REQ_SET_CONFIGURATION: // This device has 0 configurations
    send_ack(UE0D0);
    switch(ntohs(pCurrentRequest->wValue)) {
    case 1:
      break;
    case 0:                   // If config is 0, we return to address state
      reset_system();
      break;
    default:
    }			
  case REQ_CLEAR_FEATURE:
  case REQ_SET_FEATURE:
  case REQ_SET_DESCRIPTOR:
  default:
    stall();
    break;
  }
}

void send_descriptor() {
  switch(ntohs(pCurrentRequest->wValue) >> 8) {
  case DESCR_TYPE_DEVICE:
    pCurrentDescriptor = (DESCRIPTOR*)&(DeviceDescriptor);
    break;
  case DESCR_TYPE_CONFIGURATION:
    //pCurrentDescriptor = (DESCRIPTOR*)&(ConfigurationDescriptor);
    break;
  case DESCR_TYPE_STRING:
    switch((uint8)ntohs(pCurrentRequest->wValue)) {
    case LANGUAGE_INDEX:
      pCurrentDescriptor = (DESCRIPTOR*)&(LangDescriptor);
      break;
    case PRODUCT_INDEX:
      pCurrentDescriptor = (DESCRIPTOR*)&(DevStringDescriptor);
      break;
    case MANUFACTURER_INDEX:
      pCurrentDescriptor = (DESCRIPTOR*)&(ManufStringDescriptor);
      break;
    }
  default:
    stall();
    return;
  }
  if(ntohs(pCurrentRequest->wLength) < pCurrentDescriptor->bLength)
    totalBytes = ntohs(pCurrentRequest->wLength);
  else
    totalBytes = pCurrentDescriptor->bLength;
  sentBytes = 0;
  send_config();
}

void send_config() {
  uint8 byteCount = 0;

  for(; sentBytes < totalBytes && byteCount < MAX_PACKET_SIZE; byteCount++) {
    //USB_REQUEST* pCurrentPosition = pCurrentRequest + byteCount;
    *(UE0D0 + byteCount) = (uint8)pCurrentDescriptor[sentBytes++];
  }
  if(sentBytes == totalBytes)	// all data sent, force last packet
    boolLastPacket = TRUE;
  raw_send(UE0D0, byteCount);
}

void raw_send(uint8* pDataAddress, uint8 sLength) {
  if(pDataAddress == UE0D0 && sLength > 0) {
    ucr = (UCR0 & T0SEQ) | (TP0SIZ_MASK & sLength) | TX0E | RX0E;
    ucr ^= T0SEQ;     /* toggle DATA1/0 */
  }
  if(sLength == 0)      // this is just an ack, force last packet (if not done by send_config)
    boolLastPacket = TRUE;
}

void main() {
  UADDR = USBEN; /* enable usb */
  reset_system();
  asm(cli);   // enable interrupts
}
