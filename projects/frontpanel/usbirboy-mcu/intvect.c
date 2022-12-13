/**
 * intvect.c  --  USB-IR-Boy interrupt vectors
 *
 * www.sourceforge.net/projects/usbirboy/
 *
 * Copyright (c) 2004 Aapo Tamminen
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

extern void dummy_inth(void);
extern void usb_inth(void);
extern void pulse_inth(void);
extern void epoch_inth(void);
extern void _Startup(void);

void (* const intvect[])(void) @0xfff0 = {
  dummy_inth,
  epoch_inth,		// timer overflow
  dummy_inth,		// timer channel 1
  pulse_inth,		// timer channel 0
  dummy_inth,		// IRQ
  usb_inth,		// USB
  dummy_inth,		// SWI
  _Startup		// reset
};
