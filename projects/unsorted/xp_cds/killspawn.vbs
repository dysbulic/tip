Option Explicit

Const LOOP_TIMEOUT = 100
' Wait for a day is no wait is specified (24 * 60 * 60 * 1000)
Const DEFAULT_MAX_TIME = 86400000

Const WshRunning = 0
Const WshFinished = 1
Const WshFailed = 2

If WScript.Arguments.Unnamed.Count < 2 Then
  WScript.Echo "Starts a process and kills off a spawned subprocess" & VBCrLf & _
               "Usage: killspawn.vbs /wait /args:args /maxwait:ms <program> <spawn>"
  WScript.Quit(-1)
End If

Dim Program : Program = WScript.Arguments.Unnamed(0)
If WScript.Arguments.Named.Exists("args") Then
  Program = Program & " " & WScript.Arguments.Named("args")
End If

Dim WScriptShell : Set WScriptShell = WScript.CreateObject("WScript.Shell")
On Error Resume Next
Dim ProgramExec : Set ProgramExec = WScriptShell.Exec(Program)
If ProgramExec.Status = WshFailed Then
  WScript.Echo "Error: could not run: " & Program & VBCrLf & _
               "  (" & Err.Number & ") " & Err.Description
  WScript.Quit(-3)
End If
On Error GoTo 0

Dim Spawn : Spawn = WScript.Arguments.Unnamed(1)
Dim WMIURI : WMIURI = "winmgmts:{impersonationLevel=impersonate}!\\.\root\cimv2"
Dim WMIService : Set WMIService = GetObject(WMIURI)
Dim ProcessQuery : ProcessQuery = "Select * from Win32_Process Where Name = """ & Spawn & """"

Dim MaxTime : MaxTime = DEFAULT_MAX_TIME
If WScript.Arguments.Named.Exists("maxtime") Then
  MaxTime = WScript.Arguments.Named("maxtime")
End If

Dim Processes, Process, LoopCount : LoopCount = 0
Do
  Set Processes = WMIService.ExecQuery(ProcessQuery)
  If Processes.Count = 0 Then
    WScript.Sleep(LOOP_TIMEOUT)
    LoopCount = LoopCount + 1
  End If
Loop While LoopCount * LOOP_TIMEOUT < MaxTime And Processes.Count = 0

If Processes.Count > 0 Then
  For Each Process in Processes
    Process.Terminate()
  Next
Else
  WScript.Echo "Error: waited " & (LoopCount * LOOP_TIMEOUT / 1000) & " seconds for " & Spawn
  WScript.Quit(-2)
End If

If WScript.Arguments.Named.Exists("maxtime") Then
  While ProgramExec.Status = WshRunning
    WScript.Sleep(LOOP_TIMEOUT)
  WEnd
  WScript.Quit(ProgramExec.ExitCode)
End If
