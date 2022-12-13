/******************************************************************************
  FILE        : start08.c 
  PURPOSE     : 68HC08 standard startup code
  LANGUAGE    : ANSI-C / INLINE ASSEMBLER
  ----------------------------------------------------------------------------
  HISTORY 
    22 oct 93         Created.
    04/17/97          Also C++ constructors called in Init().
 ******************************************************************************/

#include "HIDEF.H"
#include "start08.h"

/**********************************************************************/
#pragma DATA_SEG FAR _STARTUP
struct _tagStartup _startupData;    /* read-only:
                                     _startupData is allocated in ROM and
                                     initialized by the linker */

#define USE_C_IMPL 0     /* for now, we are using the inline assembler implementation for the startup code */

#if !USE_C_IMPL
#pragma MESSAGE DISABLE C20001 /* Warning C20001: Different value of stackpointer depending on control-flow */
                               /* the function _COPY_L releases some bytes from the stack internally */

#pragma NO_ENTRY
#pragma NO_EXIT
#pragma NO_FRAME
static void near loadByte(void) {
  asm {
             PSHH
             PSHX
#ifdef __HCS08__
             LDHX    5,SP
             LDA     0,X
             AIX     #1
             STHX    5,SP
#else
             LDA     5,SP
             PSHA
             LDX     7,SP
             PULH
             LDA     0,X
             AIX     #1
             STX     6,SP
             PSHH
             PULX
             STX     5,SP
#endif
             PULX
             PULH
             RTS
  }
}


#endif

extern void _COPY_L(void);
/* DESC:    copy very large structures (>= 256 bytes) in 16 bit address space (stack incl.)
   IN:      TOS count, TOS(2) @dest, H:X @src
   OUT:
   WRITTEN: X,H */


#ifdef __ELF_OBJECT_FILE_FORMAT__
#define toCopyDownBegOffs 0
#else
#define toCopyDownBegOffs 2 /* for the hiware format, the toCopyDownBeg field is a long. Because the HC08 is big endian, we have to use an offset of 2 */ 
#endif
static void Init(void) {
/* purpose:     1) zero out RAM-areas where data is allocated
                2) init run-time data
                3) copy initialization data from ROM to RAM
*/
  int i;
  int *far p;
#if USE_C_IMPL   /* C implementation of ZERO OUT and COPY Down */
  int j;
  char *dst;
  _Range *far r;

  r = _startupData.pZeroOut;
  
  /* zero out */
  for(i = 0; i != _startupData.nofZeroOuts; i++) {
    dst = r->beg;
    j = r->size;
    do {
      *dst = 0; /* zero out */
      dst++;
      j--;
    } while(j != 0);
    r++;
  }
#else /* faster and smaller asm implementation for ZERO OUT */
  asm {
ZeroOut:     ;
             LDA    _startupData.nofZeroOuts:1 ; nofZeroOuts
             INCA
             STA    i:1                        ; i is counter for number of zero outs
             LDA    _startupData.nofZeroOuts:0 ; nofZeroOuts
             INCA
             STA    i:0
             LDHX   _startupData.pZeroOut      ; *pZeroOut
             BRA    Zero_5
Zero_3:    ;
           ;  CLR    i:1 is already 0
Zero_4:    ;
             ; { HX == _pZeroOut }
             PSHX
             PSHH
             ; { nof bytes in (int)2,X }
             ; { address in (int)0,X   }
             LDA    0,X
             PSHA
             LDA    2,X
             INCA
             STA    p               ; p:0 is used for high byte of byte counter
             LDA    3,X
             LDX    1,X
             PULH
             INCA
             BRA    Zero_0
Zero_1:    ;
           ;  CLRA   A is already 0, so we do not have to clear it
Zero_2:    ;
             CLR    0,X
             AIX    #1
Zero_0:    ;
             DBNZA  Zero_2
Zero_6:
             DBNZ   p, Zero_1
             PULH
             PULX                   ; restore *pZeroOut
             AIX    #4              ; advance *pZeroOut
Zero_5:    ;
             DBNZ   i:1, Zero_4
             DBNZ   i:0, Zero_3
             ;
CopyDown:    ;

  }

#endif

  /* copy down */
  /* _startupData.toCopyDownBeg  --->  {nof(16) dstAddr(16) {bytes(8)}^nof} Zero(16) */
#if USE_C_IMPL /* (optimized) C implementation of COPY DOWN */
  p = (int*far)_startupData.toCopyDownBeg;
  for(;;) {
    i = *p; /* nof */
    if(i == 0) break;
    dst = (char*far)p[1]; /* dstAddr */
    p += 2;
    do {
      /* p points now into 'bytes' */
      *dst = *((char*far)p); /* copy byte-wise */
      ((char*far)p)++;
      dst++;
      i--;
    } while(i != 0);
  }  
#elif defined(__OPTIMIZE_FOR_SIZE__)
  {

  asm {
#ifdef __HCS08__
             LDHX   _startupData.toCopyDownBeg:toCopyDownBegOffs
             PSHX  
             PSHH  
#else
             LDA    _startupData.toCopyDownBeg:(1+toCopyDownBegOffs)
             PSHA  
             LDA    _startupData.toCopyDownBeg:(0+toCopyDownBegOffs)
             PSHA  
#endif
Loop0:             
             JSR    loadByte  ; load high byte counter
             TAX              ; save for compare
             INCA  
             STA    i
             JSR    loadByte  ; load low byte counter
             INCA  
             STA    i:1
             DECA
             BNE    notfinished
             CBEQX  #0, finished
notfinished:

             JSR    loadByte  ; load high byte ptr
             PSHA  
             PULH  
             JSR    loadByte  ; load low byte ptr
             TAX              ; HX is now destination pointer
             BRA    Loop1
Loop3:             
Loop2:             
             JSR    loadByte  ; load data byte
             STA    0,X
             AIX    #1
Loop1:
             DBNZ   i:1, Loop2
             DBNZ   i:0, Loop3
             BRA    Loop0

finished:
             AIS #2
    }
  }

#else /* optimized asm version. Some bytes (ca 3) larger than C version (when considering the runtime routine too), but about 4 times faster */
  asm {
#ifdef __HCS08__
             LDHX   _startupData.toCopyDownBeg:toCopyDownBegOffs
#else
             LDX    _startupData.toCopyDownBeg:(0+toCopyDownBegOffs)
             PSHX
             PULH
             LDX    _startupData.toCopyDownBeg:(1+toCopyDownBegOffs)
#endif
next:
             LDA   0,X    ; list is terminated by 2 zero bytes
             ORA   1,X
             BEQ copydone
             PSHX         ; store current position
             PSHH
             LDA   3,X    ; psh dest low
             PSHA
             LDA   2,X    ; psh dest high
             PSHA
             LDA   1,X    ; psh cnt low
             PSHA
             LDA   0,X    ; psh cnt high
             PSHA
             AIX   #4
             JSR  _COPY_L ; copy one block
             PULH
             PULX       
             TXA
             ADD   1,X    ; add low 
             PSHA 
             PSHH
             PULA
             ADC   0,X    ; add high
             PSHA
             PULH
             PULX
             AIX   #4
             BRA next
copydone:
  }
#endif

  
  /* FuncInits: for C++, this are the global constructors */
#ifdef __cplusplus
#ifdef __ELF_OBJECT_FILE_FORMAT__
  i = _startupData.nofInitBodies - 1;
  while ( i >= 0) {
    (&_startupData.initBodies->initFunc)[i]();  /* call C++ constructors */
    i--;
  }
#else
  if (_startupData.mInits != NULL) {
    _PFunc *fktPtr;
    fktPtr = _startupData.mInits;
    while(*fktPtr != NULL) {
      (**fktPtr)(); /* call constructor */
      fktPtr++;
    }
  }
#endif     
#endif  
  /* LibInits: used only for ROM libraries */
}

#pragma NO_EXIT
#ifdef __cplusplus
  extern "C"
#endif


void _Startup (void) { /* To set in the linker parameter file: 'VECTOR 0 _Startup' */
/*  purpose:    1)  initialize the stack
                2)  initialize run-time, ...
                    initialize the RAM, copy down init dat etc (Init)
                3)  call main;
    called from: _PRESTART-code generated by the Linker
*/
asm{
MOV   #33,31// USB Reset Disable, COP Disable
CLR 10// clear TSTOP, Prescaler=0
}    

#ifdef __ELF_OBJECT_FILE_FORMAT__
  DisableInterrupts;  /* in HIWARE format, this is done in the prestart code */
#endif
for (;;) { /* forever: initialize the program; call the root-procedure */
    if (!(_startupData.flags&STARTUP_FLAGS_NOT_INIT_SP)) {
      /* initialize the stack pointer */
      INIT_SP_FROM_STARTUP_DESC();
    }                 
    Init();
    (*_startupData.main)();
  } /* end loop forever */
}

