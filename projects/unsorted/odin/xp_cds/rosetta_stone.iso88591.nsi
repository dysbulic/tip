Name "Rosetta Stone Installer"
Caption "Rosetta Stone Language Library"

OutFile "rosetta stone install.exe"

!define INSTALL_DIR "$PROGRAMFILES\Rosetta Stone"
!define UNINSTALLER "rosetta stone uninstall.exe"

; removes most of the sections so it doesn't take
; an hour to build a test version
;!define DEBUGGING

!include "MUI.nsh"
!include "LogicLib.nsh"
!include "Sections.nsh"

!macro DirIsEmpty dir jump_if_empty jump_if_not_empty
  Push $R0
  FindFirst $R0 $7 "${dir}\*.*"
  StrCmp $7 "" +6 0     ; find done: dir is empty
  StrCmp $7 "." +3 0    ; skip .
  StrCmp $7 ".." +2 0   ; skip ..
  Goto +3               ; file found; directory not empty
  FindNext $R0 $7
  Goto -5               ; check next item
  FindClose $R0         ; end find
  Pop $R0
  StrCmp "" $7 ${jump_if_empty} ${jump_if_not_empty}
!macroend

InstallDir "${INSTALL_DIR}"
InstallDirRegKey HKLM "Software\Rosetta Stone" "InstallLocation"

!ifdef DEBUGGING
  SetCompressor zlib
!else
  SetCompressor lzma
!endif

; ---------- Pages ------------

!define MUI_ICON "${NSISDIR}\Contrib\Graphics\Icons\orange-install-nsis.ico"
!define MUI_UNICON "${NSISDIR}\Contrib\Graphics\Icons\orange-uninstall-nsis.ico"
!define MUI_COMPONENTSPAGE_NODESC

!insertmacro MUI_PAGE_COMPONENTS
!insertmacro MUI_PAGE_DIRECTORY
!insertmacro MUI_PAGE_INSTFILES
  
!insertmacro MUI_UNPAGE_CONFIRM
!insertmacro MUI_UNPAGE_INSTFILES

;--------- Sections ---------------

Section "Base System"
  SectionIn RO
  WriteRegStr HKLM "Software\Rosetta Stone" "InstallLocation" "$INSTDIR"
  SetOutPath $INSTDIR
  File /r program
  WriteUninstaller "${UNINSTALLER}"
SectionEnd

!macro AddProgramLesson flags sectionindex lessonindex
  SetOutPath "$INSTDIR\data\PCT${sectionindex}_${lessonindex}\"
  File ${flags} "data\PCT${index}_${lessonindex}\"
  !insertmacro DirIsEmpty "data\PCT${index}_${lessonindex}\" 0 +3
  SetOutPath "$INSTDIR"
  RMDir "data\PCT${index}_${lessonindex}\"
!macroend

!macro AddProgramSection index
  Section "Section ${index}" SecProgram${index}
    !insertmacro AddProgramLesson "/r" "${index}" "01"
    !ifndef DEBUGGING
    !insertmacro AddProgramLesson "/r" "${index}" "02"
    !insertmacro AddProgramLesson "/r" "${index}" "03"
    !insertmacro AddProgramLesson "/r" "${index}" "04"
    !insertmacro AddProgramLesson "/r" "${index}" "05"
    !insertmacro AddProgramLesson "/r" "${index}" "06"
    !insertmacro AddProgramLesson "/r" "${index}" "07"
    !insertmacro AddProgramLesson "/r" "${index}" "08"
    !insertmacro AddProgramLesson "/r" "${index}" "09"
    !insertmacro AddProgramLesson "/r" "${index}" "10"
    !insertmacro AddProgramLesson "/nonfatal /r" "${index}" "11"
    !endif
    !insertmacro AddProgramLesson "/nonfatal /r" "${index}" "12"
  SectionEnd
!macroend

SectionGroup Program
  Section "-Indices"
    SetOutPath "$INSTDIR\data"
    File "data\cdid.trs"
    File "data\credits.trs"
  SectionEnd

  !insertmacro AddProgramSection 01
  !ifndef DEBUGGING
  !insertmacro AddProgramSection 02
  !insertmacro AddProgramSection 03
  !insertmacro AddProgramSection 04
  !insertmacro AddProgramSection 05
  !insertmacro AddProgramSection 06
  !insertmacro AddProgramSection 07
  !insertmacro AddProgramSection 08
  !insertmacro AddProgramSection 09
  !insertmacro AddProgramSection 10
  !insertmacro AddProgramSection 11
  !insertmacro AddProgramSection 12
  !insertmacro AddProgramSection 13
  !insertmacro AddProgramSection 14
  !insertmacro AddProgramSection 15
  !insertmacro AddProgramSection 16
  !insertmacro AddProgramSection 17
  !insertmacro AddProgramSection 18
  !endif
  !insertmacro AddProgramSection 19
SectionGroupEnd

Var SectionIndex
Section "-Open Section Index"
  FileOpen $SectionIndex "$INSTDIR\data\catmac.trs" w
SectionEnd

; The problem is some sections have 12 lessons and others 10.
; This is creating empty directories in the output and bad
;  indices in the catalog. I want to remove these, but it is
;  less than simple.
!macro AddLanguageLesson flags language abbreviation sectionindex lessonindex
    SetOutPath "$INSTDIR\data\${abbreviation}${sectionindex}_${lessonindex}\"
    File ${flags} "data\${abbreviation}${sectionindex}_${lessonindex}\"
    !insertmacro DirIsEmpty "$INSTDIR\data\${abbreviation}${sectionindex}_${lessonindex}\" 0 +4
    SetOutPath "$INSTDIR"
    RMDir "$INSTDIR\data\${abbreviation}${sectionindex}_${lessonindex}\"
    Goto +4
    FileWrite $SectionIndex "${language} ${sectionindex}-${lessonindex}"
    FileWriteByte $SectionIndex "13"
    FileWriteByte $SectionIndex "10"
!macroend

!macro AddLanguageSection language number
  Section "Section ${number}" Sec${language}${number}
    StrCmp "Fra" "${language}" 0 +2
    StrCpy $R0 "Français"
    StrCmp "Eng" "${language}" 0 +2
    StrCpy $R0 "English US"
    StrCmp "Ara" "${language}" 0 +2
    StrCpy $R0 "Arabic"
    StrCmp "" $R0 0 +2
    StrCpy $R0 "Unknown"

    !insertmacro AddLanguageLesson "/r" $R0 ${language} ${number} "01"
    !ifndef DEBUGGING
    !insertmacro AddLanguageLesson "/r" $R0 ${language} ${number} "02"
    !insertmacro AddLanguageLesson "/r" $R0 ${language} ${number} "03"
    !insertmacro AddLanguageLesson "/r" $R0 ${language} ${number} "04"
    !insertmacro AddLanguageLesson "/r" $R0 ${language} ${number} "05"
    !insertmacro AddLanguageLesson "/r" $R0 ${language} ${number} "06"
    !insertmacro AddLanguageLesson "/r" $R0 ${language} ${number} "07"
    !insertmacro AddLanguageLesson "/r" $R0 ${language} ${number} "08"
    !insertmacro AddLanguageLesson "/r" $R0 ${language} ${number} "09"
    !insertmacro AddLanguageLesson "/r" $R0 ${language} ${number} "10"
    !insertmacro AddLanguageLesson "/nonfatal /r" $R0 ${language} ${number} "11"
    !endif
    !insertmacro AddLanguageLesson "/nonfatal /r" $R0 ${language} ${number} "12"
  SectionEnd
!macroend

SectionGroup "Languages"
SectionGroup "English"
  !insertmacro AddLanguageSection Eng 01
  !ifndef DEBUGGING
  !insertmacro AddLanguageSection Eng 02
  !insertmacro AddLanguageSection Eng 03
  !insertmacro AddLanguageSection Eng 04
  !insertmacro AddLanguageSection Eng 05
  !insertmacro AddLanguageSection Eng 06
  !insertmacro AddLanguageSection Eng 07
  !insertmacro AddLanguageSection Eng 08
  !insertmacro AddLanguageSection Eng 09
  !insertmacro AddLanguageSection Eng 10
  !insertmacro AddLanguageSection Eng 11
  !insertmacro AddLanguageSection Eng 12
  !insertmacro AddLanguageSection Eng 13
  !insertmacro AddLanguageSection Eng 14
  !insertmacro AddLanguageSection Eng 15
  !insertmacro AddLanguageSection Eng 16
  !insertmacro AddLanguageSection Eng 17
  !insertmacro AddLanguageSection Eng 18
  !endif
  !insertmacro AddLanguageSection Eng 19
SectionGroupEnd

SectionGroup "French"
  !insertmacro AddLanguageSection Fra 01
  !ifndef DEBUGGING
  !insertmacro AddLanguageSection Fra 02
  !insertmacro AddLanguageSection Fra 03
  !insertmacro AddLanguageSection Fra 04
  !insertmacro AddLanguageSection Fra 05
  !insertmacro AddLanguageSection Fra 06
  !insertmacro AddLanguageSection Fra 07
  !insertmacro AddLanguageSection Fra 08
  !insertmacro AddLanguageSection Fra 09
  !insertmacro AddLanguageSection Fra 10
  !insertmacro AddLanguageSection Fra 11
  !insertmacro AddLanguageSection Fra 12
  !insertmacro AddLanguageSection Fra 13
  !insertmacro AddLanguageSection Fra 14
  !insertmacro AddLanguageSection Fra 15
  !insertmacro AddLanguageSection Fra 16
  !insertmacro AddLanguageSection Fra 17
  !insertmacro AddLanguageSection Fra 18
  !endif
  !insertmacro AddLanguageSection Fra 19
SectionGroupEnd

SectionGroupEnd

Section "-Close Section Index"
  FileClose $SectionIndex
SectionEnd

!macro CheckSection index
  ${If} ${SectionIsSelected} ${SecEng${index}}
  ${OrIf} ${SectionIsSelected} ${SecFra${index}}
    !insertmacro SelectSection ${SecProgram${index}}
  ${Else}
    !insertmacro UnselectSection ${SecProgram${index}}
  ${EndIf}
!macroend

Function .onSelChange
  !insertmacro CheckSection 01
  !ifndef DEBUGGING
  !insertmacro CheckSection 02
  !insertmacro CheckSection 03
  !insertmacro CheckSection 04
  !insertmacro CheckSection 05
  !insertmacro CheckSection 06
  !insertmacro CheckSection 07
  !insertmacro CheckSection 08
  !insertmacro CheckSection 09
  !insertmacro CheckSection 10
  !insertmacro CheckSection 11
  !insertmacro CheckSection 12
  !insertmacro CheckSection 13
  !insertmacro CheckSection 14
  !insertmacro CheckSection 15
  !insertmacro CheckSection 16
  !insertmacro CheckSection 17
  !insertmacro CheckSection 18
  !endif
  !insertmacro CheckSection 19
FunctionEnd

Section "Start Menu Shortcuts"
  SetOutPath "$INSTDIR"
  File "rosetta stone.cmd"
; CreateShortCut link.lnk target.file [parameters [icon.file [icon_index_number
;  [start_options [keyboard_shortcut [description]]]]]]
  CreateDirectory "$SMPROGRAMS\Rosetta Stone\"
  CreateShortCut "$SMPROGRAMS\Rosetta Stone\Rosetta Stone.lnk" "$INSTDIR\rosetta stone.cmd" "" \
                   "%SystemRoot%\system32\SHELL32.dll" 13 \
                   SW_SHOWMAXIMIZED "" "Rosetta Language Learning"
  WriteRegStr HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\Rosetta Stone" \
                    "DisplayName" "Rosetta Stone"
  WriteRegStr HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\Rosetta Stone" \
                    "UninstallString" "$\"$INSTDIR\${UNINSTALLER}$\""
  WriteRegStr HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\Rosetta Stone" \
                    "UninstallString" "$\"$INSTDIR\${UNINSTALLER}$\""
  WriteRegExpandStr HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\Rosetta Stone" \
                          "DisplayIcon" "$\"$INSTDIR\program\rosetta.exe$\""
  WriteRegDWORD HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\Rosetta Stone" \
                     "NoModify" 1
  WriteRegDWORD HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\Rosetta Stone" \
                     "NoRepair" 1
  
  CreateShortCut "$SMPROGRAMS\Rosetta Stone\Uninstall.lnk" "$INSTDIR\${UNINSTALLER}" "" \
                   "$INSTDIR\${UNINSTALLER}" 0 \
                   SW_SHOWMAXIMIZED "" "Rosetta Stone Uninstall"
SectionEnd

Section "Uninstall"
  DeleteRegKey HKLM "Software\Microsoft\Windows\CurrentVersion\Uninstall\Rosetta Stone"
  DeleteRegKey HKLM "Software\Rosetta Stone"
  RMDir /r "$INSTDIR"
  RMDir /r "$SMPROGRAMS\Rosetta Stone\"
SectionEnd
