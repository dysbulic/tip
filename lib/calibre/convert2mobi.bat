if [%1]==[] goto :usage
if %1=="" goto :usage

@set input=%1
@set output="%~dp1%~n1.mobi"

@echo Converting: %input%

@set args=--page-breaks-before="/"
@set args=%args% --chapter="//h:section/*[name()='h1' or name()='h2' or name()='h3']"

@set converter="%ProgramFiles%\Calibre2\ebook-convert.exe"
@if exist %converter% goto :convert

@set converter="%ProgramFiles(x86)%\Calibre2\ebook-convert.exe"
@if exist %converter% goto :convert

@echo This program relies on ebook-convert from Calbire
@start http://calibre-ebook.com

:convert

@set searchscript="%~dp0find_cover.js"
@set cover=
@if not exist %searchscript% goto :postcover
@for /f "tokens=*" %%l in ('cscript /nologo %searchscript% %input%') do @set cover=%%l
@if not [%cover%]==[] @set args=%args% --cover="%~dp1%cover%"

:postcover

%converter% %input% %output% %args% | tee convert2mobi.out.log
goto :end

:usage
@echo Convert ebooks to mobi format
@echo Usage: convert2mobi [input]

:end
@rem pause
@rem set /p press_return
