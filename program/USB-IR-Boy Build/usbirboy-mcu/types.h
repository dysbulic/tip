/**
 * types.h  --  USB-IR-Boy basic data types
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


#ifndef TYPES_H_INCLUDED
#define TYPES_H_INCLUDED

//#include <stddef.h>

//#define NULL ((void *)0)
#define FALSE 0
#define TRUE  1

typedef unsigned char uint8;
typedef unsigned int  uint16;
typedef unsigned long uint32;

typedef union {
  uint16 word;
  struct {
    uint8 high;
    uint8 low;
  } byte;
} uu16;

typedef union {
  uint32 dword;
  struct {
    uu16 high;
    uu16 low;
  } word;
} uu32;

#endif
