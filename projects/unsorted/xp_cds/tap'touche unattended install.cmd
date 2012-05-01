@echo off
echo Installing Tap'Touche
start /wait install -s

:: the start command exits too early, so hang out for
:: a while waiting for it to come up

:waitfornotepad
for /f %%t in ('tasklist') do if %%t == notepad.exe goto killnotepad
goto waitfornotepad
:killnotepad
echo Killing notepad
taskkill /F /IM notepad.exe
