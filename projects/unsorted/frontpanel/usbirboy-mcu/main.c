/**
 * usbcore.c  --  USB-IR-Boy main module
 *
 * www.sourceforge.net/projects/usbirboy/
 *
 * Copyright (c) 2004 Ilkka Urtamo/Aapo Tamminen
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

#include "mc68hc908jb8.h"
#include "types.h"

extern void ir_init(void);
extern void usb_init(void);

#ifndef SDCC
interrupt void dummy_inth(void) {
  nop();
}
#endif

void delay(void) {
  begin_asm
    lda #240
    delay1: dbnza delay1
  end_asm;
}

void main(void) {
  DDRA = 0xff; // make port A output
  ir_init();
  PTA = 0x02;
  usb_init();
  cli();       // enable interrupts

  for(;;) {
    int n;
    for(n = 0; n < 2000; n++)
      delay();
    PTA ^= 0x01; // toggle (LED optional on board)
  }
}
