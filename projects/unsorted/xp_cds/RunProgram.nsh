!ifndef RUN_PROGRAMS_INCLUDE
!define RUN_PROGRAMS_INCLUDE

!include "FileFunc.nsh"
!insertmacro GetDrives

Function CheckDrive
  IfFileExists "$9$R0" 0 +3
  StrCpy $R0 "$9$R0"
  StrCpy $0 StopGetDrives
  Push $0
FunctionEnd

; Checks for a program, prompting if necessary
; Input:
;  Stack 1: File to search for
;  Stack 2: Medium containing file (used in prompt)
; Output: full path of found program on stack
;  if path not found, errors are set and nothing is output
;
; Precedence is:
;  installation directory
;  executable directory
;  root of all drives alphabetically
Function FindFile
  Exch $R0      ; $R0 = Fil [$R0 Med]
  Exch          ;           [Med $R0]
  Exch $R1      ; $R1 = Med [$R1 $R0]
  Push $R2      ;           [$R2 $R1 $R0]
  StrCpy $R2 "Could not find: $R0"
  StrCmp "" $R1 +2 0
  StrCpy $R2 "$R2, please insert $R1"
  checkforfile:
  IfFileExists "$R0" found
  IfFileExists "$INSTDIR\$R0" 0 +3
  StrCpy $R0 "$INSTDIR\$R0"
  Goto found
  IfFileExists "$EXEDIR\$R0" 0 +3
  StrCpy $R0 "$EXEDIR\$R0"
  Goto found
  ${GetDrives} "ALL" "CheckDrive"
  IfFileExists $R0 found
  ; ToDo: Present a dialog to let them find the program (InstallOptions plugin)
  MessageBox MB_ABORTRETRYIGNORE|MB_ICONEXCLAMATION "$R2" \
             /SD IDIGNORE IDABORT +2 IDRETRY checkforfile
  Goto +2
  Abort "Install Canceled"
  SetErrors
  Goto +5
  found:
  Push $R0      ;           [Prog $R2 $R1 $R0]
  Exch 3        ;           [$R0 $R2 $R1 Prog]
  Exch 2        ;           [$R1 $R2 $R0 Prog]
  Exch          ;           [$R2 $R1 $R0 Prog]
  Pop $R2       ; After all that these pops have the same effect as if
  Pop $R1       ;  the program was not in the stack
  Pop $R0
FunctionEnd

; Input:
;  Stack 1: Executable to run
;  Stack 2: Arguments
;  Stack 3: Medium ("GMC Install CD#2")
Function RunProgram
  Exch $R0  ; $R0 = Fil [$R0 Arg Med]
  Exch      ;           [Arg $R0 Med]
  Exch $R1  ; $R1 = Arg [$R1 $R0 Med]
  Exch 2    ;           [Med $R0 $R1]
  Push $R0  ;           [Fil Med $R0 $R1]
  Call FindFile ;       either [Fil $R0 $R1] or [$R0 $R1]
  IfErrors 0 +3
  DetailPrint "Couldn't run $\"$R0$\" $R1; install skipped"
  Goto end
  Pop $R0   ;           [$R0 $R1]
  Push $R4
  StrCpy $R4 $R0 4 -4
  StrCmp $R4 ".vbs" 0 +2
  StrCpy $R0 'wscript "$R0"'
  Pop $R4
  ExecWait '$R0 $R1' $R1
  IntCmp 0 $R1 +2 0
  DetailPrint "$R0 exit status: $R1"
  end:
  Pop $R0
  Pop $R1
FunctionEnd

!macro AddProgram executable arguments medium
  Push "${medium}"
  Push '${arguments}'  ; There may be quotes in the argument
  Push "${executable}"
  Call RunProgram
!macroend

!endif
