@if [%1]==[] goto :usage
@if %1=="" goto :usage

@set input=%1
@set output="%~dp1%~n1.mobi"
@set args=--page-breaks-before="/"

@set converter="%ProgramFiles%\Calibre2\ebook-convert.exe"
@if exist %converter% goto :convert

@set converter="%ProgramFiles(x86)%\Calibre2\ebook-convert.exe"
@if exist %converter% goto :convert

@echo This program relies on ebook-convert from Calbire
@start http://calibre-ebook.com

:convert

@set cover=
@for /f "tokens=*" %%l in ('cscript /nologo "%~dp0find_cover.js" %input%') do @set cover=%%l
@if not [%cover%]==[] @set args=%args% --cover="%~dp1%cover%"

%converter% %input% %output% %args%
goto :end

:usage
@echo Convert ebooks to mobi format
@echo Usage: convert2mobi [input]

:end
rem pause
rem set /p press_return
