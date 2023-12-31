@echo off

if exist "%CD%\program\rosetta.exe" (
  set RosettaDir=%CD%
) else if exist "%ProgramFiles%\Rosetta Stone\" (
  set RosettaDir="%ProgramFiles%\Rosetta Stone\"
) else (
  echo Error: Rosetta Stone not installed
  pause
  goto:eof
)

if exist n: subst /d n:
subst n: "%RosettaDir%\data"
start "Rosetta" "%RosettaDir%\program\rosetta"
