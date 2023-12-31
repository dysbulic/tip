' The NSIS doesn't handle unicode, so I have to put the config in ISO-8859-1
' for the Latin characters. This means I can't make the Arabic characters
' for the Arabic student account. So, this VBScript (encoded in UTF-16 since
' the VBScript interpreter doesn't handle UTF-8) creates that account

Option Explicit

If WScript.Arguments.Count < 1 Then
  WScript.Echo "Error: Usage: add_arabic_user.vbs create_user_script"
  WScript.Quit(-1)
End If

Dim WScriptShell : Set WScriptShell = WScript.CreateObject("WScript.Shell")
WScriptShell.Run "wscript """ & WScript.Arguments(0) & """ ""طالبة"" ""طالبة أربيك"""
