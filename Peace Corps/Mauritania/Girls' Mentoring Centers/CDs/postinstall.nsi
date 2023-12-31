; This file has to be ISO-8859-1 because NSIS lacks unicode support

!include "MUI.nsh" ; Modern UI
!include "Sections.nsh"
!include "LogicLib.nsh"
!include "RadioButtons.nsh"
!include "RunProgram.nsh"

Name "GMC Postinstaller"
Caption "XP MUI and Teaching Programs"
OutFile "gmc postinstall.exe"

; The default installation directory
InstallDir "$EXEDIR"
InstallDirRegKey HKLM "Software\GMC Postinstall" "InstallLocation"
SetOverwrite ifnewer
SetDatablockOptimize on
SilentInstall normal

; -------- Pages ---------

!define MUI_COMPONENTSPAGE_SMALLDESC
!define MUI_HEADERIMAGE
!define MUI_HEADERIMAGE_BITMAP "gad header.bmp"
!define MUI_HEADERIMAGE_UNBITMAP "gad header.bmp"

!insertmacro MUI_PAGE_COMPONENTS
!insertmacro MUI_PAGE_DIRECTORY
!insertmacro MUI_PAGE_INSTFILES
  
;!insertmacro MUI_UNPAGE_CONFIRM
;!insertmacro MUI_UNPAGE_INSTFILES

!insertmacro MUI_LANGUAGE "English"

/*
Page components
Page directory
Page instfiles

UninstPage uninstConfirm
UninstPage instfiles
*/

InstType "Default"
InstType "Full"
InstType "Accounts"
InstType "Programs"

; --------- Sections -----------------------

Section
  SectionIn 1 2 3 4
  WriteRegStr HKLM "Software\GMC Postinstall" "InstallLocation" "$INSTDIR"
  SetOutPath $INSTDIR
SectionEnd

SectionGroup MUI

SectionGroup "MUI Languages" SecMUI
;Section "Français" SecFrench
Section "French" SecFrench
  SectionIn 1 2 3
  Call MUIInstall
SectionEnd
Section "Arabic" SecArabic
  SectionIn 1 2 3
  Call MUIInstall
SectionEnd
SectionGroupEnd

SectionGroup "Default Interface Language" SecDefLang
Section /o "English" SecDefEnglish
  SectionIn 1 2 3 4
SectionEnd
Section "French" SecDefFrench
  SectionIn 1 2 3
SectionEnd
Section /o "Arabic" SecDefArabic
  SectionIn 1 2 3
SectionEnd
SectionGroupEnd

SectionGroupEnd

Var MUICommand
Function MUIInstall
  StrCmp "" $MUICommand +2 0
  Return
  StrCpy $MUICommand "/r /i"
  ${If} ${SectionIsSelected} ${SecFrench}
    StrCpy $MUICommand "$MUICommand 040C"
  ${EndIf}
  ${If} ${SectionIsSelected} ${SecArabic}
    StrCpy $MUICommand "$MUICommand 0401"
  ${EndIf}
  ${If} ${SectionIsSelected} ${SecDefArabic}
    StrCpy $MUICommand "$MUICommand /d 0401"
  ${ElseIf} ${SectionIsSelected} ${SecDefFrench}
    StrCpy $MUICommand "$MUICommand /d 040C"
  ${Else}
    StrCpy $MUICommand "$MUICommand /d 0409"
  ${EndIf}
  !insertmacro AddProgram "mui\muisetup.exe" "$MUICommand" "GMC XP CD#1"
FunctionEnd

Var AddUserScript
!macro AddUser username fullname
  StrCmp "" $AddUserScript 0 +3
  File create_user.vbs
  StrCpy $AddUserScript "$INSTDIR\create_user.vbs"
  Exec 'wscript "$AddUserScript" "${username}" "${fullname}"'
!macroend

SectionGroup "Account Options"

SectionGroup "Student Accounts"
Section "English" SecEnglishAcc
  SectionIn 1 2 3
  !insertmacro AddUser "student" "English Student"
SectionEnd
Section "French" SecFrenchAcc
  SectionIn 1 2 3
  !insertmacro AddUser "étudiant" "Ètudiant Français"
SectionEnd
Section "Arabic" SecArabicAcc
  SectionIn 1 2 3
  StrCmp "" $AddUserScript 0 +3
  File create_user.vbs
  StrCpy $AddUserScript "$INSTDIR\create_user.vbs"
  File create_arabic_student.utf16.vbs
  Exec 'wscript "$INSTDIR\create_arabic_student.utf16.vbs" "$INSTDIR\create_user.vbs"'
SectionEnd
SectionGroupEnd

Var AddJITScript
Section "Personalize Student Accounts" SecPersonalize
  SectionIn 1 2 3
  StrCmp "" $AddJITScript 0 +3
  File add_jit_program.vbs
  StrCpy $AddJITScript "add_jit_program.vbs"
  File english_student.reg
  File french_student.reg
  File arabic_student.reg
  File personalize_student_account.utf16.vbs
  Exec 'wscript $AddJITScript -s "$INSTDIR\personalize_student_account.utf16.vbs" "JIT Account Personalize"'
  File mauritania_flag.bmp
  File flag_screen_saver.reg
  Exec 'wscript $AddJITScript -r "$INSTDIR\flag_screen_saver.reg" "Mauritania Flag Screen Saver"'
SectionEnd

Section "Restrict Student Accounts" SecRestrict
  SectionIn 1 2 3
  StrCmp "" $AddJITScript 0 +3
  File add_jit_program.vbs
  StrCpy $AddJITScript "add_jit_program.vbs"
  File restrict_student_account.utf16.vbs
  File xcacls.vbs
  File lockdown.reg
  Exec 'wscript $AddJITScript -s "$INSTDIR\restrict_student_account.utf16.vbs" "JIT Account Lockdown"'
SectionEnd

Function GetParent ; from the documentation
  Exch $R0
  Push $R1
  Push $R2
  Push $R3
  StrCpy $R1 0
  StrLen $R2 $R0

  IntOp $R1 $R1 + 1
  IntCmp $R1 $R2 +4 0 +4
  StrCpy $R3 $R0 1 -$R1
  StrCmp $R3 "\" +2
  Goto -4

  StrCpy $R0 $R0 -$R1

  Pop $R3
  Pop $R2
  Pop $R1
  Exch $R0
FunctionEnd

Section "Cleanup System" SecCleanup
  SectionIn 1 2 3

  RMDir /r "$PROGRAMFILES\msn gaming zone\"
  RMDir /r "$PROGRAMFILES\online services\"
  RMDir /r "$PROGRAMFILES\microsoft frontpage\"
  RMDir /r "$PROGRAMFILES\xerox\"

  Push "$PROFILE"
  Call GetParent
  Pop $R0
  StrCpy $R1 "$R0\all users\start menu"
  DetailPrint "Deleting: $R1\windows update.lnk"
  Delete "$R1\windows update.lnk"
  Delete "$R1\windows catalog.lnk"
  StrCpy $R1 "$R1\programs\accessories"
  RMDir /r "$R1\communications\"
  Delete "$R1\system tools\backup.lnk"
  Delete "$R1\system tools\disk cleanup.lnk"
  Delete "$R1\system tools\disk defragmenter.lnk"
  Delete "$R1\system tools\scheduled tasks.lnk"
  StrCpy $R1 "$R0\default user\start menu\programs\"
  Delete "$R1\remote assistance.lnk"
SectionEnd

Section "Google Search" SecGoogle
  SectionIn 1 2 3
  WriteRegStr HKLM "SOFTWARE\Microsoft\Internet Explorer\Search" \
                   "SearchAssistant" "http://www.google.com/ie"
SectionEnd

Section "Disable System Restore"
  SectionIn 1 2 3
  WriteRegDWORD HKLM "SOFTWARE\Policies\Microsoft\Windows NT\SystemRestore" "DisableConfig" 1
  WriteRegDWORD HKLM "SOFTWARE\Policies\Microsoft\Windows NT\SystemRestore" "DisableSR" 1
SectionEnd

SectionGroupEnd

Var DefLangTracker
Function .onInit
  StrCpy $DefLangTracker ${SecDefFrench}
  Call Programs.onInit
FunctionEnd

Function .onSelChange
  ; This version of the radiobuttons macros is 
  !insertmacro WJHStartRadioButtons $DefLangTracker
    !insertmacro WJHRadioButton ${SecDefEnglish}
    !insertmacro WJHRadioButton ${SecDefFrench}
    !insertmacro WJHRadioButton ${SecDefArabic}
  !insertmacro WJHEndRadioButtons

  ; If the default language is not English or if there is to be an account
  ;  created for a language, that MUI has to be installed
  SectionGetFlags ${SecDefArabic} $R0
  SectionGetFlags ${SecArabicAcc} $R1
  IntOp $R0 $R0 | $R1
  IntOp $R0 $R0 & ${SF_SELECTED}
  IntCmp $R0 ${SF_SELECTED} 0 checkfrench checkfrench
  !insertmacro SelectSection ${SecArabic}

  checkfrench:
  SectionGetFlags ${SecDefFrench} $R0
  SectionGetFlags ${SecFrenchAcc} $R1
  IntOp $R0 $R0 | $R1
  IntOp $R0 $R0 & ${SF_SELECTED}
  IntCmp $R0 ${SF_SELECTED} 0 checkdone checkdone
  !insertmacro SelectSection ${SecFrench}

  checkdone:

  Call Programs.onSelChange
FunctionEnd

!include gmc_programs.iso88591.nsi

Section "Uninstall"
;  !insertmacro AddProgram "mui\muisetup.exe" "/u" "GMC XP CD#1"
  DeleteRegKey HKLM "Software\GMC Postinstall"
SectionEnd

; --------- Descriptions -------------------

LangString DESC_SecMUI ${LANG_ENGLISH} "Windows XP Multilanguage User Interface"
LangString DESC_SecDefLang ${LANG_ENGLISH} "Language used for welcome screen"
LangString DESC_SecRestrict ${LANG_ENGLISH} "Limit students' access to channge system settings"
LangString DESC_SecPersonalize ${LANG_ENGLISH} "Set students' homepage, background and screensaver"
LangString DESC_SecGoogle ${LANG_ENGLISH} "Set the default search engine to google"

!insertmacro MUI_FUNCTION_DESCRIPTION_BEGIN
  !insertmacro MUI_DESCRIPTION_TEXT ${SecMUI} $(DESC_SecMUI)
  !insertmacro MUI_DESCRIPTION_TEXT ${SecDefLang} $(DESC_SecDefLang)
  !insertmacro MUI_DESCRIPTION_TEXT ${SecRestrict} $(DESC_SecRestrict)
  !insertmacro MUI_DESCRIPTION_TEXT ${SecPersonalize} $(DESC_SecPersonalize)
  !insertmacro MUI_DESCRIPTION_TEXT ${SecGoogle} $(DESC_SecGoogle)
!insertmacro MUI_FUNCTION_DESCRIPTION_END
