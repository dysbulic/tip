/* HC08 software serial transmit for debugging purpoces: TX=PTA1x, 19200 baud, 1 stop bit */
/* Aapo Tamminen, 2005 */

#include "mc68hc908jb8.h"
#define TX 1

void sio_init(void) {
  PTA |= (1<<TX);
}

void sio_putc(unsigned char c) {
  begin_asm
	pshx			 ;  3
	psha			 ;  2
	lda	#47		 ;  2	(156-(3+2+2+3+4))/3 = 47
	bclr	#TX,*_PTA	 ;  4
delay0: dbnza	delay0		 ;  3x
	pula			 ;  3
	ldx	#0x08		 ;  2
sloop:	bit	#0x01		 ;  2
	beq	zero		 ;  3
one:	bset	#TX,*_PTA	 ;  4
	bra	next		 ;  3
zero:	bclr	#TX,*_PTA	 ;  4
	bra	next		 ;  3
next:	lsra			 ;  1
	psha			 ;  3
	lda	#44		 ;  2	(156-(3+1+2+2+3+3+2+3+4))/3 = 44
delay1: dbnza   delay1		 ;  3x
	pula			 ;  3
	dbnzx	sloop		 ;  3
	lda	#2		 ;  2
delay3: dbnza	delay3		 ;  3x
	lda	#52		 ;  2	(156)/3 = 52
	bset	#TX,*_PTA	 ;  4
delay2: dbnza	delay2		 ;  3x
	pulx			 ;  3
  end_asm;
}
