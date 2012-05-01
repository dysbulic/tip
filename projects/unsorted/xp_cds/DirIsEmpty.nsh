!ifndef DIR_IS_EMPTY_HEADER
!define DIR_IS_EMPTY_HEADER

; This macro overwrites $7, but allows for relative jumps
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

!endif
