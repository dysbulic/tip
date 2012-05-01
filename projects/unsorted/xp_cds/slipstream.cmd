@echo off
:: commands to copy xp cd and slipstream the service pack
:: also copies in files used in GMC unattended install
:: iso should be mounted with sudo mount -o loop ~/isos/win_xp.iso ~/isos/origxp/

set SourceDir=w:\isos\origxp
set DestDir=c:\temp\xp_cd
set SetupDir=w:\isos\xpsetup
set XP_SP=xpsp1a_en_x86.exe
set CDImage=c:\temp\cdimage\cdimagegui.exe

if exist "%DestDir%" (
  echo Warning: %DestDir% already exists; skipping copy
) else (
  xcopy /e "%SourceDir%" "%DestDir%\"
)
if exist "%DestDir%\win51ip.sp*" (
  echo Service pack already slipstreamed; skipping
) else (
  if not exist "%SetupDir%\%XP_SP%" (
    echo Error: %SetupDir%\%XP_SP% doesn't exist
    goto:eof
  )
  "%SetupDir%\%XP_SP%" -s:"%DestDir%"
)
xcopy /y "%SetupDir%\winnt.sif" "%DestDir%\i386\"
xcopy /y /e "%SetupDir%\$OEM$" "%DestDir%\$OEM$\"
if exist "%DestDir%\mui\" (
  echo MUI install files already present; skipping copy
) else (
  xcopy /y /e "%SetupDir%\mui" "%DestDir%\mui\"
)

:: image is now 704mb and won't burn on a single CD
:: remove the 9x migration to make space
if exist "%DestDir%\i386\win9xmig\" rmdir /s /q "%DestDir%\i386\win9xmig\"

if exist %CDImage% (
  echo Starting CD Image creator
  start %CDImage%
) else (
  echo Warning: "%CDImage%" not found
)

