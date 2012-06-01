<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
  <title>Ladies and Gents Hair Salon</title>
  <base href="http://www.ladiesandgents.biz">
  <script language="javascript" ><!--
    btPicUp = new Image();
    btPicDown = new Image();
    btLoginUp = new Image();
    btLoginDown = new Image();

    btPicUp.src = "graphics/btPictures.gif";
    btPicDown.src = "graphics/btDownPictures.gif";
    btLoginUp.src = "graphics/btLogin.gif";
    btLoginDown.src = "graphics/btDownLogin.gif";
  --></script>
  <link rel="stylesheet" type="text/css" href="main.php">
  </head>
  <body background="graphics/mainback.jpg">
    <table border width ="100%">
      <tr>
        <td width=155 valign=top>
          <table BORDER>
            <tr><td><img src="graphics/menu.gif"></td></tr>
            <tr><td><a href="Photos.php" onmouseover="document.btPic.src=btPicDown.src" onmouseout="document.btPic.src=btPicUp.src" alt ="pictures"><img src="graphics/btPictures.gif" name="btPic"></a></td></tr>
            <tr><td><a href="login.php" alt="login" onmouseover="document.btLogin.src=btLoginDown.src" onmouseout="document.btLogin.src=btLoginUp.src"><img src="graphics/btLogin.gif" name="btLogin"></a></td></tr>
          </table>
        </td>
        <td width=25></td>
        <td>
          <img src="graphics/title.gif">
          <br>
          <h2>Welcome to the future home of ladies and gents hair salon! We are located at the The Wellington Place.
          <br><br>
            400 Professional Park Drive<br>
            Kingsport, TN    37663<br>
            (423)239-0174<br>
            Olivia Stelley, owner/operator. <br>
          <br><br>
           This site is currently still under construction.</h2>
        </td>
      </tr>
    </table>
  </body>
</html>