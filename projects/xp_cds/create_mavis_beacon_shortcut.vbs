Option Explicit

' Creates a shortcut to the actual mavis beacon executable

Dim WScriptShell : Set WScriptShell = WScript.CreateObject("WScript.Shell")
Dim LinkFilename
LinkFilename = "%AllUsersProfile%\start menu\programs\Mavis Beacon Teaches Typing.lnk"
Dim Shortcut
Set Shortcut = WScriptShell.CreateShortcut(WScriptShell.ExpandEnvironmentStrings(LinkFilename))
   
Shortcut.TargetPath = "%ProgramFiles%\Mavis Beacon\Mavis15.exe"
'Shortcut.Arguments = ""
Shortcut.Description = "Mavis Beacon Typing Tutor 15"
'Shortcut.HotKey = "ALT+CTRL+F"
Dim IconFilename
IconFilename = "%ProgramFiles%\Mavis Beacon\mav2.ico"
Shortcut.IconLocation = WScriptShell.ExpandEnvironmentStrings(IconFilename)
'Shortcut.WindowStyle = "1"
Shortcut.WorkingDirectory = "%ProgramFiles%\Mavis Beacon\"
Shortcut.Save
