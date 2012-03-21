Option Explicit

Dim Username, RegistryFile
Username = CreateObject("Wscript.Network").Username

Select Case Username
  Case "student"  : RegistryFile = "english_student.reg"
  Case "étudiant" : RegistryFile = "french_student.reg"
  Case "طالبة"    : RegistryFile = "arabic_student.reg"
End Select

If RegistryFile <> "" Then
  Dim WScriptShell : Set WScriptShell = WScript.CreateObject("WScript.Shell")
  RegistryFile = WScript.Path & "\" & RegistryFile
  Dim FSO : Set FSO = WScript.CreateObject("Scripting.FileSystemObject")
  If FSO.FileExists(RegistryFile) Then
    WScriptShell.Run "%WinDir%\regedit /s """ & RegistryFile & """"
  Else
    WScript.Echo "Error: Missing registry file: " & RegistryFile
  End If
End If
