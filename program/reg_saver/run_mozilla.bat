set mozillabase=u:\mozilla-profiles
set mozillaprogram=%mozillabase%\..\mozilla\mozilla
set appdir=c:\Documents and Settings\%USERNAME%\Application Data\Mozilla

if "%1" == "setup" goto SETUP

:RUN
mkdir "%appdir%"
copy "%mozillabase%\*.dat" "%appdir%\"
start /MAX %mozillaprogram% -P Will

goto END

rem The profile that you create here should be the same name
rem as the option that follows -P above (mine was Will)

:SETUP
mkdir "%mozillabase%"
%mozillaprogram%  -profilemanager
copy "%appdir%\*.dat" "%mozillabase%\"

:END
