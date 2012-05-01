/**
 * usbcoce.h  --  USB-IR-Boy MCU USB firmware.
 *
 * www.sourceforge.net/projects/usbirboy/
 *
 * Copyright (c) 2004 Ilkka Urtamo
 * Modified 2006 Will Holcomb <wholcomb@gmail.com>
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

#ifndef __USBCORE_H
#define __USBCORE_H

#include "types.h"

#define SEND_BUFF_LEN	   128	/*in bytes, intervals of 8 (max 256 == uint8)*/
#define RECEIVE_BUFF_LEN   16	/*in bytes, intervals of 8 (max 256 == uint8)*/

#define VENDOR_ID           0xFFFE   /* manufacturer id (chosen at random from the end of the list) */
#define PRODUCT_ID	    0x0000   /* product id */
#define PRODUCT_VER	    0x0021   /* product version */

#define SELF_POWERED	    0        /* set to 0 if using USB power, 1 otherwise*/

/* function definitions */
uint8 usb_isdata();		// is there data in receive buffer
uint8 usb_getc(uint8*);		// get char from receiver buffer
void  usb_init();	        // initialize usb
uint8 usb_putc(uint8 c);	// send char

#endif
