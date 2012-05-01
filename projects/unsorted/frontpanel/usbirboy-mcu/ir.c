/**
 * ir.c  --  USB-IR-Boy IR receiver (TSOP 173x)
 *
 * www.sourceforge.net/projects/usbirboy/
 *
 * Copyright (c) 2004 Aapo Tamminen
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

extern uu32 frbcode;
extern void frbwrite(void);

volatile uint8 epoch;
volatile uint8 start_epoch;
volatile uint8 end_epoch;
volatile uu16 start_time;
volatile uu16 end_time;

void ir_init(void) {
  TSC = TSTOP | TRST;
  TMODH = 0xff;
  TMODL = 0xff;
  TSC0 = CHIE | ELSA | ELSB;
  TSC = TOIE;
}

#ifdef SDCC
void epoch_inth(void) interrupt 6
#else
interrupt void epoch_inth(void)
#endif
{
  uint8 u = TSC;
  uint8 new_epoch = epoch + 1;
  if(new_epoch != start_epoch)
    epoch = new_epoch;
  TSC = u & ~TOF;
}

#ifdef SDCC
void pulse_inth(void) interrupt 4
#else
interrupt void pulse_inth(void)
#endif
{
  uint8 u = TSC0;
  uint8 pulse = ((PTE & 2) >> 1);
  
  end_time.byte.high = TCH0H;
  end_time.byte.low = TCH0L;
  end_epoch = epoch;
  
  frbcode.word.high.byte.high = 0;
  frbcode.word.high.byte.low = end_epoch - start_epoch;
  if (end_time.word <= start_time.word)
    frbcode.word.high.byte.low--;
  frbcode.word.low.word = end_time.word - start_time.word;
  frbcode.dword /= 3;
  frbcode.word.high.byte.high = pulse;
  frbwrite();
  
  start_epoch = end_epoch;
  start_time.word = end_time.word;
  
  TSC0 = u & ~CHF;
}
