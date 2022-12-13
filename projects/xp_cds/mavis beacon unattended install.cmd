@echo off

echo Installing Mavis Beacon
start setup -s

:: The registration program is run before the setup program
:: ends, so background the setup and wait for it

set PROG=EReg32.exe

:waitforreg
for /f %%t in ('tasklist') do if %%t == %PROG% goto killreg
goto waitforreg
:killreg
echo Killing off registration
:: There are three screens, kill them all
taskkill /IM %PROG%
taskkill /IM %PROG%
taskkill /IM %PROG%

:: WebReg Class ID: HKEY_CLASSES_ROOT\CLSID\{6A6B8868-73D8-40FB-9E38-B047A8F170F7}
:: WebReg Type Lib: HKEY_CLASSES_ROOT\TypeLib\{577CBDDD-C490-44EA-BE75-D1B5A38316B5}
:: Settings: HKEY_LOCAL_MACHINE\SOFTWARE\Broderbund
:: Registration: HKEY_LOCAL_MACHINE\SOFTWARE\Registration\Machine
