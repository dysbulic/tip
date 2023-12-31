Option Explicit
'On Error Resume Next

Const ADS_UF_SCRIPT = &H0001
Const ADS_UF_ACCOUNTDISABLE = &H0002
Const ADS_UF_HOMEDIR_REQUIRED = &H0008
Const ADS_UF_LOCKOUT = &H0010
Const ADS_UF_PASSWD_NOTREQD = &H0020
Const ADS_UF_PASSWD_CANT_CHANGE = &H0040
Const ADS_UF_ENCRYPTED_TEXT_PASSWORD_ALLOWED = &H0080
Const ADS_UF_DONT_EXPIRE_PASSWD = &H10000
Const ADS_UF_SMARTCARD_REQUIRED = &H40000
Const ADS_UF_PASSWORD_EXPIRED = &H800000

Function UserExists(ComputerName, Username)
  Dim ManagementURI, UserSet, User
  'ManagementURI = "winmgmts:{impersonationLevel=impersonate}!//" & ComputerName
  'Set UserSet = GetObject(ManagementURI).InstancesOf("Win32_UserAccount")
  
  Set UserSet = GetObject("WinNT://" & ComputerName)
  UserSet.Filter = Array("user")

  For Each User In UserSet
    If LCase(User.Name) = LCase(Username) Then
      UserExists = TRUE
      Exit Function
    End If
  Next
  UserExists = FALSE
End Function

Sub CreateUser(ComputerName, Username, FullName)
  Dim User
  If Not UserExists(ComputerName, Username) Then
    Set User = GetObject("WinNT://" & ComputerName).Create("User", Username)
  Else
    Set User = GetObject("WinNT://" & ComputerName & "/" & Username & ",user")
  End If
  User.FullName = FullName
  'User.Description = Description
  User.SetPassword("")
  User.SetInfo ' If this fails, I don't know how to gracefully handle the error
               ' The function exits even with On Error Resume Next Set

  Dim Flags
  Flags = User.Get("UserFlags")

  'User.AccountDisabled = FALSE
  User.Put "UserFlags", Flags OR ADS_UF_PASSWD_NOTREQD _
                              OR ADS_UF_PASSWD_CANT_CHANGE _
                              OR ADS_UF_DONT_EXPIRE_PASSWD
  User.SetInfo

  Dim UsersGroup, UserInGroup, Account
  Set UsersGroup = GetObject("WinNT://" & ComputerName & "/Users,group")
  
  For Each Account In UsersGroup.Members
    If Account.ADSPath = User.ADSPath Then
      UserInGroup = TRUE
    End If
  Next
  
  If Not UserInGroup Then
    UsersGroup.Add(User.ADsPath)
    UsersGroup.SetInfo
  End If
End Sub

If WScript.Arguments.Count < 1 Then
  WScript.Echo "Too few arguments: Usage: create_user username [full name]"
  WScript.Quit(-1)
End If

Dim ComputerName : ComputerName = CreateObject("Wscript.Network").ComputerName
Dim FullName : FullName = ""
If WScript.Arguments.Count > 1 Then
  FullName = WScript.Arguments(1)
End If

CreateUser ComputerName, WScript.Arguments(0), FullName

If Err.Number <> 0 Then
  WScript.Echo "Error: (" & Hex(Err.Number) & "): " & Err.Description
  WScript.Quit(Err.Number)
End If
