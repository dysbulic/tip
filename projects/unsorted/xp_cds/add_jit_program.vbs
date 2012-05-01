Option Explicit

Const HKEY_CURRENT_USER = &H80000001
Const HKEY_LOCAL_MACHINE = &H80000002

' Having some problems passing quotes in. No time to fix it proper

Dim ProgramType, BaseIndex
BaseIndex = 0
If WScript.Arguments.Count >= 1 And WScript.Arguments(0) = "-s" Then
  ProgramType = "script"
  BaseIndex = 1
ElseIf WScript.Arguments.Count >= 1 And WScript.Arguments(0) = "-r" Then
  ProgramType = "registry"
  BaseIndex = 1
End If

Dim Program
If WScript.Arguments.Count < BaseIndex + 1 Then
  WScript.Echo "Adds a program to the active setup. It will be run each time a" & vbCrLF _
               & " new account logs in for the first time" & vbCrLF _
               & "Usage: add_jit_program [-s|-r] <REG_EXPAND_SZ program> [component id]"
  WScript.Quit(-1)
Else
  Program = """" & WScript.Arguments(BaseIndex) & """"
  Select Case ProgramType
    Case "script"   : Program = "%WinDir%\system32\wscript " & Program
    Case "registry" : Program = "%WinDir%\regedit /s " & Program
  End Select
End If

Dim ComputerName, Registry, ComponentsKey
ComputerName = "."
Set Registry = GetObject("winmgmts:{impersonationLevel=impersonate}!\\" _
                         & ComputerName & "\root\default:StdRegProv")
ComponentsKey = "Software\Microsoft\Active Setup\Installed Components\"

' First make sure the program isn't already being run

Dim InstalledComponents, Component, StubPath, ProgramGUID
Registry.EnumKey HKEY_LOCAL_MACHINE, ComponentsKey, InstalledComponents

Dim WScriptShell : Set WScriptShell = WScript.CreateObject("WScript.Shell")
Dim ExpandedProgram : ExpandedProgram = WScriptShell.ExpandEnvironmentStrings(Program)

For Each Component In InstalledComponents
  Registry.GetStringValue HKEY_LOCAL_MACHINE, ComponentsKey & "\" & Component, _
                                  "StubPath", StubPath
  If LCase(StubPath) = LCase(ExpandedProgram) Then
    ProgramGUID = Component
  End If
Next

If ProgramGUID = "" Then
  Dim GUID : GUID = Left(CreateObject("Scriptlet.TypeLib").GUID, 38)
  Dim ComponentKey : ComponentKey = ComponentsKey & "\" & GUID
  Registry.CreateKey HKEY_LOCAL_MACHINE, ComponentKey

  Registry.SetExpandedStringValue HKEY_LOCAL_MACHINE, ComponentKey, "StubPath", Program
  If WScript.Arguments.Count > BaseIndex + 1 Then
    Registry.SetStringValue HKEY_LOCAL_MACHINE, ComponentKey, "ComponentID", _
                            WScript.Arguments(BaseIndex + 1)
  End If
  Registry.SetDWordValue HKEY_LOCAL_MACHINE, ComponentKey, "IsInstalled", 1
Else
'  WScript.Echo """" & Program & """ already being run at:" & VBCrLf _
'               & "  " & ProgramGUID
  WScript.Quit(-1)
End If
