Option Explicit

Const LOOP_TIMEOUT = 100 ' Number of milliseconds to wait
Const MAX_LOOPS = 100    ' Maximum number of loops

Const WshRunning = 0
Const WshFinished = 1
Const WshFailed = 2

If WScript.Arguments.Unnamed.Count < 1 Then
  WScript.Echo "Runs a program and sends keystrokes to it" & VBCrLf & _
               "Usage: sendkeys.vbs <executable> /args:args /wait [key]+"
  WScript.Quit(-2)
End If

Dim WScriptShell : Set WScriptShell = WScript.CreateObject("WScript.Shell")
Dim Program : Program = WScript.Arguments.Unnamed(0)
Dim FSO : Set FSO = WScript.CreateObject("Scripting.FileSystemObject")
If FSO.FileExists(WScript.Path & "\" & Program) Then
  Program = WScript.Path & "\" & Program
End If
If WScript.Arguments.Named.Exists("args") Then
  Program = Program & " " & WScript.Arguments.Named("args")
End If

On Error Resume Next
Dim ProgramExec : Set ProgramExec = WScriptShell.Exec(Program)
If ProgramExec.Status = WshFailed Then
  WScript.Echo "Error: could not run: " & Program
  WScript.Echo "  (" & Err.Number & ") " & Err.Description
  WScript.Quit(-3)
End If
On Error GoTo 0

Dim AppFound : AppFound = False
Dim LoopCount : LoopCount = 0
While Not AppFound
  AppFound = WScriptShell.AppActivate(ProgramExec.ProcessID)
  If Not AppFound Then
    LoopCount = LoopCount + 1
    If LoopCount > MAX_LOOPS Then
      WScript.Echo "Error: waited " & (MAX_LOOPS * LOOP_TIMEOUT / 1000) _
                   & " seconds for " & Program & "; giving up"
      WScript.Quit(-1)
    Else
      WScript.Sleep(LOOP_TIMEOUT)
    End If
  End If
WEnd

Dim ArgumentIndex
For ArgumentIndex = 1 to WScript.Arguments.Unnamed.Count - 1
  WScriptShell.SendKeys WScript.Arguments.Unnamed(ArgumentIndex)
Next

If WScript.Arguments.Named.Exists("wait") Then
  While ProgramExec.Status = WshRunning
    WScript.Sleep(LOOP_TIMEOUT)
  WEnd
  WScript.Quit(ProgramExec.ExitCode)
End If
