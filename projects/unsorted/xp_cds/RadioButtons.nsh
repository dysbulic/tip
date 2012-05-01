/*
The existing radio buttons implementation works by taking the existing
selected item, disabling it and then seeing if there is another item
selected. If so it leaves the item deselected causing there to be only
one at a time. Except this doesn't work if the radio buttons are
children of a group and the parent is selected causing all the
children to be selected at once.
*/

!ifndef WJH_RADIOBUTTONS_INCLUDED

!define WJH_RADIOBUTTONS_INCLUDED

!include "Sections.nsh"

!macro WJHStartRadioButtons var
  !define StartRadioButtons_Var "${var}"
  Push $R0
  Push $R1
  StrCpy $R1 ""
  ClearErrors
!macroend

!macro WJHRadioButton SECTION_NAME
  SectionGetFlags ${SECTION_NAME} $R0
  IntOp $R0 $R0 & ${SF_SELECTED}
  IntCmp $R0 ${SF_SELECTED} 0 +5 +5  ; if not selected skip to next
  StrCmp ${SECTION_NAME} "${StartRadioButtons_Var}" +4 0 ; if the last selected, skip on
  StrCmp $R1 "" +2 0 ; if another section is selected as well, that's a problem
  SetErrors
  StrCpy $R1 ${SECTION_NAME}
  !insertmacro UnselectSection ${SECTION_NAME}
!macroend

!macro WJHEndRadioButtons
  IfErrors 0 +2
  Goto +3 ; if there were multiples, leave the selected item the same
  StrCmp $R1 "" +2 0 ; make sure there was a change
  StrCpy "${StartRadioButtons_Var}" $R1
  !insertmacro SelectSection "${StartRadioButtons_Var}"
  Pop $R1
  Pop $R0
  !undef StartRadioButtons_Var
!macroend

!endif
