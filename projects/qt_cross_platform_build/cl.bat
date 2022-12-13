@rem Copied from: c:\Program Files\Microsoft Visual Studio 9.0\Common7\Tools\vsvars32.bat

@set VSInstallDir=C:\Program Files\Microsoft Visual Studio 9.0
@set VCInstallDir=C:\Program Files\Microsoft Visual Studio 9.0\VC
@set FrameworkDir=C:\WINDOWS\Microsoft.NET\Framework
@set FrameworkVersion=v2.0.50727
@set Framework35Version=v3.5
@if "%VSInstallDir%"=="" goto error_no_vsinstalldir
@if "%VCInstallDir%"=="" goto error_no_vcinstalldir

@echo Setting environment for using Microsoft Visual Studio 2008 x86 tools.

@call :GetWindowsSdkDir

@if not "%WindowsSdkDir%" == "" (
	set "PATH=%WindowsSdkDir%bin;%PATH%"
	set "INCLUDE=%WindowsSdkDir%include;%INCLUDE%"
	set "LIB=%WindowsSdkDir%lib;%LIB%"
)


@rem
@rem Root of Visual Studio IDE installed files.
@rem
@set DevEnvDir=C:\Program Files\Microsoft Visual Studio 9.0\Common7\IDE

@set PATH=C:\Program Files\Microsoft Visual Studio 9.0\Common7\IDE;C:\Program Files\Microsoft Visual Studio 9.0\VC\BIN;C:\Program Files\Microsoft Visual Studio 9.0\Common7\Tools;C:\WINDOWS\Microsoft.NET\Framework\v3.5;C:\WINDOWS\Microsoft.NET\Framework\v2.0.50727;C:\Program Files\Microsoft Visual Studio 9.0\VC\VCPackages;%PATH%
@set INCLUDE=C:\Program Files\Microsoft Visual Studio 9.0\VC\ATLMFC\INCLUDE;C:\Program Files\Microsoft Visual Studio 9.0\VC\INCLUDE;%INCLUDE%
@set LIB=C:\Program Files\Microsoft Visual Studio 9.0\VC\ATLMFC\LIB;C:\Program Files\Microsoft Visual Studio 9.0\VC\LIB;%LIB%
@set LIBPATH=C:\Windows\Microsoft.NET\Framework\v3.5;C:\WINDOWS\Microsoft.NET\Framework\v2.0.50727;C:\Program Files\Microsoft Visual Studio 9.0\VC\ATLMFC\LIB;C:\Program Files\Microsoft Visual Studio 9.0\VC\LIB;%LIBPATH%

@rem Run cl
"%VCInstallDir%\bin\cl" %*

@goto end

:GetWindowsSdkDir
@call :GetWindowsSdkDirHelper HKLM > nul 2>&1
@if errorlevel 1 call :GetWindowsSdkDirHelper HKCU > nul 2>&1
@if errorlevel 1 set WindowsSdkDir=%VCInstallDir%\PlatformSDK\
@exit /B 0

:GetWindowsSdkDirHelper
@for /F "tokens=1,2*" %%i in ('reg query "%1\Software\Microsoft\Microsoft SDKs\Windows" /v "CurrentInstallFolder"') DO (
	if "%%i"=="CurrentInstallFolder" (
		SET "WindowsSdkDir=%%k"
	)
)
@if "%WindowsSdkDir%"=="" exit /B 1
@exit /B 0

:error_no_vsinstalldir
@echo Error: VSInstallDir variable is not set. 
@goto end

:error_no_VCINSTALLDIR
@echo Error: VCInstallDir variable is not set. 
@goto end

:end
