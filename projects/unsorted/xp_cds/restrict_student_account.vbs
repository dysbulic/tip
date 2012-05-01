Option Explicit

Const READONLY_FILE = 1
Const HIDDEN_FILE = 2
Const SYSTEM_FILE = 4
Const ARCHIVE_FILE = 32
Const LINK_FILE = 64
Const COMPRESSED_FILE = 2048

Dim Lockdown
Dim WScriptNetwork : Set WScriptNetwork = CreateObject("WScript.Network")

Select Case WScriptNetwork.Username
  Case "student"  : Lockdown = True
  Case "étudiant" : Lockdown = True
  Case "طالبة"    : Lockdown = True
End Select

If Lockdown Then
  Dim WScriptShell : Set WScriptShell = WScript.CreateObject("WScript.Shell")
  RegistryFile = WScript.Path & "\lockdown.reg"
  Dim FSO : Set FSO = WScript.CreateObject("Scripting.FileSystemObject")
  If FSO.FileExists(RegistryFile) Then
    WScriptShell.Run "%WinDir%\regedit /s """ & RegistryFile & """"
  Else
    WScript.Echo "Error: Missing registry file: " & RegistryFile
  End If

  Dim XCACLs : XCACLs = WScript.Path & "\xcacls.vbs"
  If FSO.FileExists(XCACLs) Then
    Dim Files : Files = Array("%UserProfile%\Start Menu\", _
                              "%UserProfile%\Desktop\")
    Dim File
    For Each File In Files
      WScriptShell.Run "wscript /b """ & XCACLs & """ """ & File & """ /Q /E " _
                       & "/D " & WScriptNetwork.ComputerName & "\" _
                       & WScriptNetwork.Username & ":W"
    Next
  Else
    WScript.Echo "Error: Missing xcacls program: " & XCACLs
  End If
End If
