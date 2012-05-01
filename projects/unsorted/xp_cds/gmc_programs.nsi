!include "RunProgram.nsh"

!macro AddScriptedProgram script executable arguments medium
  ClearErrors
  Push "${medium}"
  Push "${executable}"
  Call FindFile
  IfErrors noprog
  Pop $R0
  File ${script}
  !insertmacro AddProgram "${script}" '${arguments}' "Install File"
  noprog:
!macroend

SectionGroup "GMC Programs"

  Section "Office 2003 French" SecOffice
    SectionIn 1 2 4
    AddSize 358300
    !insertmacro AddProgram "Office 2003 Français\setup.exe" \
                 "TRANSFORMS=office_2003.mst /qb+" "GMC Install CD#2"
    ; Remove Smart Tags
    ExecWait 'msiexec /I{C45577A3-F6BF-46AD-91F7-8474B770D595} /qb-'
  SectionEnd

SectionGroup "Typing Tutor"
  Section "Mavis Beacon" SecMavis
    SectionIn 1 2 4
    AddSize 178000
    ClearErrors
    Push "GMC Install CD #3"
    Push "mavis_beacon_teaches_typing\setup.exe"
    Call FindFile
    IfErrors nomavis
    Pop $R0
    File killspawn.vbs
    !insertmacro AddProgram "killspawn.vbs" '/wait "$R0" /args:-s ereg32.exe' \
                 "Install File"
    SetShellVarContext all
    Delete "$SMSTARTUP\personal coach.lnk"
    RMDir /r "$SMPROGRAMS\Broderbund\"
    CreateShortCut "$SMPROGRAMS\Mavis Beacon Teaches Typing.lnk" \
                   "%ProgramFiles%\Mavis Beacon\Mavis15.exe" \
                   "" "%ProgramFiles%\Mavis Beacon\mav2.ico" 0 \
                   SW_SHOWMAXIMIZED "" "Mavis Beacon Typing Tutor 15"
    nomavis:
  SectionEnd

  Section "Tap'Touche" SecTap
    SectionIn 1 2 4
    AddSize 63600
    !insertmacro AddScriptedProgram "killspawn.vbs" "tap'touche\install.exe" \
    '/wait "$R0" /args:-s notepad.exe' "GMC Programs #3"
  SectionEnd
SectionGroupEnd

Section "Rosetta Stone" SecRosetta
  SectionIn 1 2 4
  AddSize 949500
  !insertmacro AddProgram "rosetta stone install.exe" "/S" "Rosetta Stone"
SectionEnd

!define ENCARTA_DIR "$PROGRAMFILES\Microsoft Encarta\2003 Contents\E03FSTRC\"

SectionGroup Encyclopedia
  Section "Encarta French" SecEncarta
    SectionIn 1 2 4
    AddSize 91600
    !insertmacro AddProgram "encarta\install.exe" "/qb-" "Encarta Encyclopédie"
    IfErrors +4
    CreateDirectory "${ENCARTA_DIR}"
    Call GetParent ; AddProgram left the name of the installer on the top of the stack
    Pop $R0
    CopyFiles "$R0\content\*.*" "${ENCARTA_DIR}" 411000
    WriteRegStr HKLM "Software\Microsoft\Microsoft Reference\E03FSTRC" \
                "CHDPath" "${ENCARTA_DIR}"
  SectionEnd

  Section /o "Arabic Encyclopedia" SecArabEnc
    SectionIn 1 4
    AddSize 5000000
    !insertmacro AddProgram "arab_encyclopedia\install.exe" "" "Arabic Encyclopedia"
  SectionEnd
SectionGroupEnd

SectionGroup Utilities
  Section "Seven Zip" SecSevenZip
    SectionIn 1 2 4
    AddSize 2600
    !insertmacro AddProgram "7-zip.4.32.exe" "/S" "GMC Programs #2"
  SectionEnd

  Section "Flash Player" SecFlash
    SectionIn 1 2 4
    AddSize 1000
    !insertmacro AddProgram "flash_player.8.0.22.exe" "/S" "GMC Programs #2"
  SectionEnd

  Section "Java Runtime" SecJava
    SectionIn 1 2 4
    AddSize 85000
    !insertmacro AddProgram "j2re.1.4.2.exe" '/S /v /qb WEBSTARTICON=0' "GMC Programs #2"
  SectionEnd
SectionGroupEnd

SectionGroup Multimedia
  Section "Audacity Sound Editor" SecAudacity
    SectionIn 1 2 4
    AddSize 7400
    !insertmacro AddProgram "audacity.1.2.3.exe" "/silent" "GMC Programs #2"
  SectionEnd

  SectionGroup Adobe
    SectionGroup "Acrobat Reader" ; %systemroot%\Cache\Adobe Reader 6.0
      Section /o "English" SecReaderEn
        AddSize 2000
        !insertmacro AddProgram "adobe_reader.6.0.en.exe" "" "Adobe Programs"
      SectionEnd
      Section "French" SecReaderFr
        SectionIn 1 2 4
        AddSize 2000
        !insertmacro AddProgram "adobe_reader.6.0.fr.exe" "" "Adobe Programs"
      SectionEnd
      Section /o "Arabic" SecReaderAr
        AddSize 2000
        !insertmacro AddProgram "adobe_reader.6.0.ar.exe" "" "Adobe Programs"
      SectionEnd
    SectionGroupEnd

Var DefReaderTracker
Function Programs.onInit
  StrCpy $DefReaderTracker ${SecReaderFr}
FunctionEnd

Function Programs.onSelChange
  !insertmacro WJHStartRadioButtons $DefReaderTracker
    !insertmacro WJHRadioButton ${SecReaderEn}
    !insertmacro WJHRadioButton ${SecReaderFr}
    !insertmacro WJHRadioButton ${SecReaderAr}
  !insertmacro WJHEndRadioButtons
FunctionEnd

    Section "Illustrator French" SecIllustrator
      SectionIn 1 2 4
      AddSize 176000
      !insertmacro AddProgram "adobe illustrator 10\setup\setup.exe" "-s" "Adobe Programs"
      AddSize 21000
      !insertmacro AddProgram "acrobat distiller 5.0.5\setup.exe" "-s" "Adobe Programs"
    SectionEnd

    Section "PageMaker English" SecPageMaker
      SectionIn 1 2 4
      AddSize 136000
      !insertmacro AddProgram "adobe pagemaker 7.0.1\setup\setup.exe" "-s" "Adobe Programs"
    SectionEnd
    Section "Photoshop French" SecPhotoshop
      SectionIn 1 2 4
      AddSize 265000
      !insertmacro AddProgram "adobe Photoshop 7.0\setup\setup.exe" "-s" "Adobe Programs"
    SectionEnd
    Section "Premiere French" SecPremiere
      SectionIn 1 2 4
      AddSize 41000
      !insertmacro AddProgram "adobe premiere 6.0\setup\setup.exe" "-s" "Adobe Programs"
    SectionEnd
    Section "SVG Viewer" SecSVG
      SectionIn 1 2 4
      AddSize 3000
      !insertmacro AddProgram "svg_viewer.3.03.exe" "" "Adobe Programs"
    SectionEnd
  SectionGroupEnd

  Section "DivX Codec" SecDivX
    SectionIn 1 2 4
    AddSize 14700
    !insertmacro AddProgram "divx_player.6.0.3.exe" "/S" "GMC Programs #2"
    ExecWait '"$PROGRAMFILES\DivX\DivXPlayerUninstall.exe" /S'
  SectionEnd

  SectionGroup IrfanView
    Section "IrfanView" SecIrfan
      SectionIn 1 2 4
      AddSize 5000
      !insertmacro AddScriptedProgram "sendkeys.vbs" "irfanview.3.92.exe" \
        '/wait "$R0" "{TAB}{TAB} {TAB}{TAB}{DOWN}{TAB} {TAB} ~~ %n%n~~"' "GMC Programs #2"
    SectionEnd
    Section "IrfanView Plugins" SecIrfanPlugins
      SectionIn 1 2 4
      AddSize 3000
      !insertmacro AddScriptedProgram "sendkeys.vbs" "irfanview_plugins.3.92.exe" \
        '/wait "$R0" "%n~"' "GMC Programs #2"
    SectionEnd
  SectionGroupEnd

  Section "Real Player" SecReal
    SectionIn 1 2 4
    AddSize 2000
    !insertmacro AddProgram "real_player.10.5.exe" "/S" "GMC Programs #2"
  SectionEnd

  Section "WinAMP" SecWinAMP
    SectionIn 1 2 4
    AddSize 6500
    !insertmacro AddProgram "winamp.5.12.exe" "/S /install=SFQR" "GMC Programs #2"
    ExecWait '"$PROGRAMFILES\Winamp\eMusic\Uninst-eMusic-promotion.exe" /S'
  SectionEnd
SectionGroupEnd

SectionGroup Games
  Section "Celestia" SecCelestia
    SectionIn 1 2 4
    AddSize 2000
    !insertmacro AddScriptedProgram "sendkeys.vbs" "celestia1.3.1-1.exe" \
                 '"$R0" /args:/silent %%y' "GMC Programs #2"
  SectionEnd

  Section "PySolitaire" SecPySol
    SectionIn 1 2 4
    AddSize 24500
    !insertmacro AddProgram "ActivePython-2.4.2-248-win32-ix86.msi" \
                 '/qb INSTALLDIR="$PROGRAMFILES\Python24\"' "GMC Programs #2"
    !insertmacro AddProgram "pysolitaire.0.10.lite.exe" "/silent" "GMC Programs #2"
  SectionEnd
SectionGroupEnd

SectionGroupEnd
