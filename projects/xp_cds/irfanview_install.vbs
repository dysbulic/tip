Option Explicit

Dim WScriptShell : Set WScriptShell = WScript.CreateObject("WScript.Shell")
Dim IrfanInstaller
IrfanInstaller = WScriptShell.CurrentDirectory & "\irfanview.3.92.exe"
Dim FSO : Set FSO = WScript.CreateObject("Scripting.FileSystemObject")
If Not FSO.FileExists(IrfanInstaller) Then
  WScript.Echo "IrfanView installer missing: " & IrfanInstaller
  WScript.Quit(-1)
Else
  WScriptShell.Run IrfanInstaller
  Dim AppFound : AppFound = False
  While Not AppFound
    WScript.Sleep 100
    AppFound = WScriptShell.AppActivate("IrfanView Setup")
  WEnd
  WScriptShell.SendKeys "{TAB}"
  WScriptShell.SendKeys "{TAB}"
  WScriptShell.SendKeys " "
  WScriptShell.SendKeys "{TAB}"
  WScriptShell.SendKeys "{TAB}"
  WScriptShell.SendKeys "{DOWN}"
  WScriptShell.SendKeys "{TAB}"
  WScriptShell.SendKeys " "
  WScriptShell.SendKeys "{TAB}"
  WScriptShell.SendKeys " "
  WScriptShell.SendKeys "{ENTER}"
  WScriptShell.SendKeys "{ENTER}"
  WScriptShell.SendKeys " "
  WScriptShell.SendKeys "%n"
  WScriptShell.SendKeys "%n"
  WScriptShell.SendKeys "%y"
  WScriptShell.SendKeys "{ENTER}"
End If
