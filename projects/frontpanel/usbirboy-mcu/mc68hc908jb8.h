/**
 * mc68hc908jb8.h  --  USB-IR-Boy MCU register definitions
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

#ifndef MC68HC908JB8_H_INCLUDED
#define MC68HC908JB8_H_INCLUDED

#ifdef SDCC
volatile xdata at 0x00 unsigned char PTA;
volatile xdata at 0x04 unsigned char DDRA;
volatile xdata at 0x08 unsigned char PTE;
volatile xdata at 0x0a unsigned char TSC;
volatile xdata at 0x0c unsigned char TCNTH;
volatile xdata at 0x0d unsigned char TCNTL;
volatile xdata at 0x0e unsigned char TMODH;
volatile xdata at 0x0f unsigned char TMODL;
volatile xdata at 0x10 unsigned char TSC0;
volatile xdata at 0x11 unsigned char TCH0H;
volatile xdata at 0x12 unsigned char TCH0L;
volatile xdata at 0x13 unsigned char TSC1;
volatile xdata at 0x14 unsigned char TCH1H;
volatile xdata at 0x15 unsigned char TCH1L;


/* usb register definitions */
volatile xdata at 0x0018 unsigned char R_UIR2;
volatile xdata at 0x0019 unsigned char R_UCR2;
volatile xdata at 0x001A unsigned char R_UCR3;
volatile xdata at 0x001B unsigned char R_UCR4;
volatile xdata at 0x0038 unsigned char R_UADDR;

volatile xdata at 0x0039 unsigned char R_UIR0;
volatile xdata at 0x003A unsigned char R_UIR1;
volatile xdata at 0x003B unsigned char R_UCR0;
volatile xdata at 0x003C unsigned char R_UCR1;
volatile xdata at 0x003D unsigned char R_USR0;
volatile xdata at 0x003E unsigned char R_USR1;

/* buffers */
#define ADD_UDATAEND0 	((unsigned char*)0x20)		/*usb endpoint 0 xdata buffers, 8 bytes starting from here */
#define ADD_UDATAEND1 	((unsigned char*)0x28)		/*usb endpoint 1 xdata buffers, 8 bytes starting from here */
#define ADD_UDATAEND2 	((unsigned char*)0x30)		/*usb endpoint 2 xdata buffers, 8 bytes starting from here */

#define cli()  _asm cli _endasm;
#define nop() _asm nop _endasm;

#define begin_asm _asm
#define end_asm _endasm

#else

/* CodeWarrior */
#define PTA  *((uint8 *) 0x00)
#define DDRA *((uint8 *) 0x04)
#define PTE  *((uint8 *) 0x08)

#define TSC 	*((uint8 *) 0x0a)
#define TCNTH	*((uint8 *) 0x0c)
#define TCNTL	*((uint8 *) 0x0d)
#define TMODH	*((uint8 *) 0x0e)
#define TMODL	*((uint8 *) 0x0f)
#define TSC0	*((uint8 *) 0x10)
#define TCH0H	*((uint8 *) 0x11)
#define TCH0L	*((uint8 *) 0x12)
#define TSC1	*((uint8 *) 0x13)
#define TCH1H	*((uint8 *) 0x14)
#define TCH1L	*((uint8 *) 0x15)

/* usb register definitions */
#define R_UIR2  *((volatile uint8 *) 0x0018)
#define R_UCR2  *((volatile uint8 *) 0x0019)
#define R_UCR3  *((volatile uint8 *) 0x001A)
#define R_UCR4  *((volatile uint8 *) 0x001B)
#define R_UADDR *((volatile uint8 *) 0x0038)
#define R_UIR0  *((volatile uint8 *) 0x0039)
#define R_UIR1  *((volatile uint8 *) 0x003A)
#define R_UCR0  *((volatile uint8 *) 0x003B)
#define R_UCR1  *((volatile uint8 *) 0x003C)
#define R_USR0  *((volatile uint8 *) 0x003D)
#define R_USR1  *((volatile uint8 *) 0x003E)

/* buffers */
#define ADD_UDATAEND0 	((uint8*)0x20)		/* usb endpoint 0 data buffer, 8 bytes starting from here */
#define ADD_UDATAEND1 	((uint8*)0x28)		/* usb endpoint 1 data buffer, 8 bytes starting from here */
#define ADD_UDATAEND2 	((uint8*)0x30)		/* usb endpoint 2 data buffer, 8 bytes starting from here */

#define cli() asm("cli");
#define nop() asm("nop");

#define begin_asm asm {
#define end_asm }

#endif

// TSC bits
#define TOF   (1<<7)	// overflow flag
#define TOIE  (1<<6)	// overflow interrupt enable
#define TSTOP (1<<5)	// stop
#define TRST  (1<<4)	// reset
#define PS2   (1<<2)	// prescaler
#define PS1   (1<<1)
#define PS0   (1<<0)

// TSC0/1 bits
#define CHF   (1<<7)	// channel flag: active edge (input) / counter match (output)
#define CHIE  (1<<6)	// interrupt enable
#define MSB   (1<<5)	// enable buffered output compare/PWM
#define MSA   (1<<4)	// enable unbuffered output compare/PWM
#define ELSB  (1<<3)	// capture on falling edge
#define ELSA  (1<<2)	// capture on rising edge
#define TOV   (1<<1)	// toggle on overflow
#define CHMAX (1<<0)	// maximum duty cycle

/* usb register bit definitions */

/* UADDR */
#define USBEN		0x80
#define UADD_MASK	0x7F
/* UIR0 */
#define EOPIE		0x80
#define SUSPND		0x40
#define TXD2IE		0x20
#define RXD2IE		0x10
#define TXD1IE		0x08
#define TXD0IE		0x02
#define RXD0IE		0x01
/* UIR1 */
#define EOPF		0x80
#define RSTF		0x40
#define TXD2F		0x20
#define RXD2F		0x10
#define TXD1F		0x08
#define RESUMF		0x04
#define TXD0F		0x02
#define RXD0F		0x01
/* UIR2 */
#define EOPFR		0x80
#define RSTFR		0x40
#define TXD2FR		0x20
#define RXD2FR		0x10
#define TXD1FR		0x08
#define RESUMFR		0x04
#define TXD0FR		0x02
#define RXD0FR		0x01
/* UCR0 */
#define T0SEQ		0x80
#define TX0E		0x20
#define RX0E		0x10
#define TP0SIZ_MASK	0x0F
/* UCR1 */
#define T1SEQ		0x80
#define STALL1		0x40
#define TX1E		0x20
#define FRESUM		0x10
#define TP1SIZ_MASK	0x0F
/* UCR2 */
#define T2SEQ		0x80
#define STALL2		0x40
#define TX2E		0x20
#define RX2E		0x10
#define TP2SIZ_MASK	0x0F
/* UCR3 */
#define TX1ST		0x80
#define TX1STR		0x40
#define OSTALL0		0x20
#define ISTALL0		0x10
#define PULLEN		0x04
#define ENABLE2		0x02
#define ENABLE1		0x01
/* UCR4 */
#define FUSB0 		0x04
#define FDP		0x02
#define FDM		0x01
/* USR0*/
#define RSEQ		0x80
#define SETUP		0x40
#define RP0SIZ_MASK	0x0F
/* USR1*/
#define R2SEQ		0x80
#define TXACK		0x40
#define TXNAK		0x20
#define TXSTL		0x10
#define RP2SIZ_MASK	0x0F

#endif /* end inclusion guard */
