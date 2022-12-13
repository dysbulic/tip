/*      $Id: filter.c,v 1.4 2005/05/03 07:26:28 aaptam Exp $      */

/****************************************************************************
 ** lirc_serial.c ***********************************************************
 ****************************************************************************
 *
 * lirc_serial - Device driver that records pulse- and pause-lengths
 *               (space-lengths) between DDCD event on a serial port.
 *
 * Copyright (C) 1996,97 Ralph Metzler <rjkm@thp.uni-koeln.de>
 * Copyright (C) 1998 Trent Piepho <xyzzy@u.washington.edu>
 * Copyright (C) 1998 Ben Pfaff <blp@gnu.org>
 * Copyright (C) 1999 Christoph Bartelmus <lirc@bartelmus.de>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 */

/* Steve's changes to improve transmission fidelity:
     - for systems with the rdtsc instruction and the clock counter, a 
       send_pule that times the pulses directly using the counter.
       This means that the LIRC_SERIAL_TRANSMITTER_LATENCY fudge is
       not needed. Measurement shows very stable waveform, even where
       PCI activity slows the access to the UART, which trips up other
       versions.
     - For other system, non-integer-microsecond pulse/space lengths,
       done using fixed point binary. So, much more accurate carrier
       frequency.
     - fine tuned transmitter latency, taking advantage of fractional
       microseconds in previous change
     - Fixed bug in the way transmitter latency was accounted for by
       tuning the pulse lengths down - the send_pulse routine ignored
       this overhead as it timed the overall pulse length - so the
       pulse frequency was right but overall pulse length was too
       long. Fixed by accounting for latency on each pulse/space
       iteration.

   Steve Davies <steve@daviesfam.org>  July 2001
*/

/* frbwrite() modified for usbirboy by Aapo Tamminen  September 2004 */

#include "types.h"

extern void usb_putc(uint8);

static uu32 pulse,space;
static uint8 ptr;
static uu32 sendcode;

static void usb_send(void)
{
	usb_putc(sendcode.word.low.byte.low | 0x80);
	sendcode.dword <<= 1;
	usb_putc(sendcode.word.low.byte.high & 0x7f);
	sendcode.dword <<= 1;
	usb_putc(sendcode.word.high.byte.low & 0x7f);
	sendcode.dword <<= 1;
	usb_putc(sendcode.word.high.byte.high & 0x7f);
}

#define PULSE_BIT 1
uu32 frbcode;
void frbwrite(void)
{
	/* simple noise filter */
	if(ptr>0 && (frbcode.word.high.byte.high&PULSE_BIT))
	{
		frbcode.word.high.byte.high = 0;
		pulse.dword+=frbcode.dword;
		if(pulse.dword>250)
		{
			sendcode.dword=space.dword;
			usb_send();
			pulse.word.high.byte.high|=PULSE_BIT;
			sendcode.dword=pulse.dword;
			usb_send();
			ptr=0;
			pulse.dword=0;
		}
		return;
	}
	if(!(frbcode.word.high.byte.high&PULSE_BIT))
	{
		if(ptr==0)
		{
			if(frbcode.dword>20000)
			{
				space.dword=frbcode.dword;
				ptr++;
				return;
			}
		}
		else
		{
			if(frbcode.dword>20000)
			{
				space.dword+=pulse.dword;
				if (space.word.high.byte.high != 0)
				{
					space.word.high.word = 0x00ff;
					space.word.low.word = 0xffff;
				}
				space.dword+=frbcode.dword;
				if (space.word.high.byte.high != 0)
				{
					space.word.high.word = 0x00ff;
					space.word.low.word = 0xffff;
				}
				pulse.dword=0;
				return;
			}
			sendcode.dword=space.dword;
			usb_send();
			pulse.word.high.byte.high|=PULSE_BIT;
			sendcode.dword=pulse.dword;
			usb_send();
			ptr=0;
			pulse.dword=0;
		}
	}
	sendcode.dword=frbcode.dword;
	usb_send();
}
