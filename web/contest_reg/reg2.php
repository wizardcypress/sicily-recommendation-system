<?
	require("./checktime.php");
?>
<html>
<head>
<title>Register</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" href="style.css">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td>
	<?
	  require("./navigation.php");
	?>
    </td>
  </tr>
  <tr> 
    <td width="100%" align=center><table width="770" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="150" height="100" bgcolor="#F0F0F0"></td>
          <td width="470" rowspan="3" align="center" valign="top">
		    <br>
		    <form action="re_reg.php" method="post">
		    <input name="type" value="zsucpc" type="hidden">
              <table border="0" cellpadding="4" cellspacing="2">
                <tr bgcolor="#0071BD" class="white"> 
                  <td height="20" colspan="2" align="center"><b>ZSUCPC Register Form 
                    </b></td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Team name &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="teamname" size="30" maxlength="50"> 
                  </td>
                </tr>
		<tr bgcolor="#EEEEEE">
		  <td height="20" align="right">Contest Position &nbsp;</td>
		  <td align="left">&nbsp;
		    <input name="position" size="30" maxlength="50">
		  </td>
		</tr>
                <tr bgcolor="#0071BD" height="20">
                  <td height="20" colspan="2" align="center" class="white">
                    <b>The First Contestant (Team Leader)
                    </b>
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Name &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input type="text" name="name1" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Sex &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="sex1" type="radio" id="sex1" size="30" maxlength="50" class="radio" value="Male">Male
		    &nbsp; &nbsp; &nbsp;
                    <input name="sex1" type="radio" id="sex1" size="30" maxlength="50" class="radio" value="Female">Female
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Department &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="department1" type="text" id="department1" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Grade &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="grade1" type="text" id="grade1" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Class &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="class1" type="text" id="class1" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Email &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="email" size="30" maxlength="50">
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td width="130" height="20" align="right">Telephone &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="telephone" type="text" id="telephone" size="30" maxlength="20">
                  </td>
                </tr>
                <tr bgcolor="#0071BD" height="20">
                  <td height="20" colspan="2" align="center" class="white">
                    <b>The Second Contestant
                    </b>
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Name &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input type="text" name="name2" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Sex &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="sex2" type="radio" id="sex2" size="30" maxlength="50" class="radio" value="Male">Male
		    &nbsp; &nbsp; &nbsp;
                    <input name="sex2" type="radio" id="sex2" size="30" maxlength="50" class="radio" value="Female">Female
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Department &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="department2" type="text" id="department2" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Grade &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="grade2" type="text" id="grade2" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Class &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="class2" type="text" id="class2" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#0071BD" height="20">
                  <td height="20" colspan="2" align="center" class="white">
                    <b>The Third Contestant
                    </b>
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Name &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input type="text" name="name3" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Sex &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="sex3" type="radio" id="sex1" size="30" maxlength="50" class="radio" value="Male">Male
		    &nbsp; &nbsp; &nbsp;
                    <input name="sex3" type="radio" id="sex1" size="30" maxlength="50" class="radio" value="Female">Female
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Department &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="department3" type="text" id="department3" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Grade &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="grade3" type="text" id="grade3" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Class &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="class3" type="text" id="class3" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr align="center" bgcolor="#FCFCFC"> 
                  <td height="20" colspan="2" align="center"> 
                    <input type="submit" value="Register" name="addsub">
                  </td>
                </tr>
                </tr>
              </table>
            </form>
          </td>
          <td width="150" height="100" bgcolor="#F0F0F0"></td>
        </tr>
      </table> </td>
    <td>&nbsp;</td>
  </tr>
<?
  require("./footer.php");
?>
</table>
</body>
</html>
