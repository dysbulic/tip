<?
$relpath = "../";
include ($relpath . "top.php");
?>
<font face="verdana, geneva, tahoma, arial"><B>Donate Land for Our Ads</b></font>

<p><font face="verdana, geneva, tahoma, arial" size="-1"><Interested in donating your land for one of our billboards? Use the form to find out how you can get our ads on your land!</font> 
</p>

<form method="post" action="<? echo $PHP_SELF ?>">
  <table width="500" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="150" valign="top" align="right"><font face="Verdana, Geneva, Tahoma, Arial" size="-1">name:</font></td>
      <td valign="top"> 
        <input type="text" name="name" size="50">
      </td>
    </tr>
    <tr> 
      <td width="150" valign="top" align="right"><font face="Verdana, Geneva, Tahoma, Arial" size="-1">address:</font></td>
      <td valign="top"> 
        <input type="text" name="address" size="50">
      </td>
    </tr>
    <tr> 
      <td width="150" valign="top" align="right"><font face="Verdana, Geneva, Tahoma, Arial" size="-1">city:</font></td>
      <td valign="top"> 
        <input type="text" name="city" size="50">
      </td>
    </tr>
    <tr> 
      <td width="150" valign="top" align="right"><font face="Verdana, Geneva, Tahoma, Arial" size="-1">state:</font></td>
      <td valign="top"> 
        <input type="text" name="state" size="50">
      </td>
    </tr>
    <tr> 
      <td width="150" valign="top" align="right"><font face="Verdana, Geneva, Tahoma, Arial" size="-1">zip 
        code:</font></td>
      <td valign="top"> 
        <input type="text" name="zip" size="50">
      </td>
    </tr>
    <tr> 
      <td width="150" valign="top" align="right"><font face="Verdana, Geneva, Tahoma, Arial" size="-1">phone:</font></td>
      <td valign="top"> 
        <input type="text" name="phone" size="50">
      </td>
    </tr>
    <tr> 
      <td width="150" valign="top" align="right"><font face="Verdana, Geneva, Tahoma, Arial" size="-1">email:</font></td>
      <td valign="top"> 
        <input type="text" name="email" size="50">
      </td>
    </tr>
    <tr> 
      <td width="150" valign="top" align="right"><font face="Verdana, Geneva, Tahoma, Arial" size="-1">preferred 
        contact:</font></td>
      <td valign="top"> 
        <input type="radio" name="contact_method" value="radiobutton">
        <font face="Verdana, Geneva, Tahoma, Arial" size="-1">email</font><br>
        <input type="radio" name="contact_method" value="radiobutton">
        <font face="Verdana, Geneva, Tahoma, Arial" size="-1">phone</font><br>
        <input type="radio" name="contact_method" value="radiobutton">
        <font face="Verdana, Geneva, Tahoma, Arial" size="-1">snail mail</font></td>
    </tr>
    <tr> 
      <td width="150" valign="top" align="right"><font face="Verdana, Geneva, Tahoma, Arial" size="-1">note:</font></td>
      <td valign="top"> 
        <textarea name="note" cols="50" rows="15"></textarea>
      </td>
    </tr>
    <tr> 
      <td colspan="2" valign="top" align="right">
        <input type="submit" name="send" value="send">
      </td>
    </tr>
  </table>
</form><? include ($relpath . "right.php"); ?>