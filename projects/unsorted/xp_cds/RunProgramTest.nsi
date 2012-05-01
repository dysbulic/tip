!include "RunProgram.nsh"
!ifndef FILEFUNC_VERBOSE
  !include "FileFunc.nsh"
!endif

!insertmacro GetParameters

Name "Run Program Test"
OutFile "RunProgram Test.exe"
SetOverwrite ifnewer

Page components
Page instfiles

!macro CheckRegister num
  StrCmp $R${num} "${num}" +4 0
  DetailPrint "  Error Register ${num} is $\"$R${num}$\""
  StrCpy $R${num} "${num}"
  SetErrors
!macroend

!macro CheckRegisters
  DetailPrint "Checking Registers:"
  ClearErrors
  !insertmacro CheckRegister 0
  !insertmacro CheckRegister 1
  !insertmacro CheckRegister 2
  !insertmacro CheckRegister 3
  !insertmacro CheckRegister 4
  IfErrors +2 0
  DetailPrint "  Success: Registers R[0-4] OK"
!macroend

Section Exists
  DetailPrint "Testing a file known to exist"
  !insertmacro AddProgram "RunProgram Test.exe" 'This is an "internal test"' "Testing"
  !insertmacro CheckRegisters
SectionEnd

Section "Send Keys"
  DetailPrint "Testing sendkeys"
  File "sendkeys.vbs"
  !insertmacro AddProgram "sendkeys.vbs" \
   "/wait notepad.exe $\"This string has spaces in it!~$\" This string has more spaces" "Testing"
  !insertmacro CheckRegisters
SectionEnd

Section /o "Start Command"
  DetailPrint "Testing the start command"
  ExecShell "" "start notepad"
SectionEnd

Section "Does Not Exist"
  DetailPrint "Testing a file known not to exist"
  !insertmacro AddProgram "file.that.does.not.exist" "" "Testing"
  !insertmacro CheckRegisters
SectionEnd

Function .onInit
  System::Call 'kernel32::CreateMutexA(i 0, i 0, t "RunProgramMutex") i .r1 ?e'
  Pop $R0
  StrCmp $R0 0 end
  ${GetParameters} $0
  MessageBox MB_OK|MB_ICONEXCLAMATION "The installer is already running ($0)"
  Abort
  end:
  StrCpy $R0 "0"
  StrCpy $R1 "1"
  StrCpy $R2 "2"
  StrCpy $R3 "3"
  StrCpy $R4 "4"
FunctionEnd
