<!-- --- prevent direct all --- -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>

<!-- --- charset setting --- -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<meta name="keywords" content="">

<!-- --- output page title --- -->
<title>SMART3 - Error Page</title>

<!-- --- use allways the php relative path definition to include media files --- -->
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/smart.css" rel="stylesheet" type="text/css">
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/typography.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
   function subok(s){
      s.value = "wait ...";
   }
</script>
<style type="text/css">
<!--
.unnamed1 {
  padding-top: 1px;
  padding-right: 2px;
  padding-bottom: 3px;
  padding-left: 4px;
}
.unnamed2 {
  margin-top: 1px;
  margin-right: 2px;
  margin-bottom: 3px;
  margin-left: 4px;
}
.login {
    border: thin solid #0000CC;
    background-color: #CCCCCC;
}
.loginbody {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    color: #333333;
}
.logintitle {
    font-size: 12px;
    font-weight: bold;
    color: #660099;
    border-top-width: 0px;
    border-top-style: none;
    border-bottom-color: #0000CC;
    border-right-width: 0px;
    border-bottom-width: 1px;
    border-left-width: 0px;
    border-right-style: none;
    border-bottom-style: solid;
    border-left-style: none;
    background-color: #66CCFF;
}
.loginitem {
    font-size: 10px;
    font-weight: bold;
    color: #333333;
}
.loginerror {
    font-size: 10px;
    font-weight: bold;
    color: #CC0000;
}
.logintext {
    font-size: 10px;
    color: #333333;
}
.loginbutton {
    font-size: 9px;
    color: #000066;
    background-color: #CCCC66;
    font-weight: bold;
    letter-spacing: 3px;
}
-->
</style>
</head>

<body>

<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="maintab">
  <tr>
    <td>
    
    <!-- --- include header view --- -->
    <?php $viewLoader->header();?></td>
  </tr>
  <tr>
    <td><table width="760" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="120" align="left" valign="top" bgcolor="#2096DB" class="leftcol">
            
            <!-- --- include main navigation menu links view --- -->
            <?php $viewLoader->mainNavigation();?>  
        </td>
        <td width="638" align="left" valign="top">
         <table width="638" border="0" cellspacing="4" cellpadding="2">
          <tr>
            <td align="left" valign="top"><form name="form1" method="post" action="<?php echo SMART_CONTROLLER; ?>?view=login">
              <table width="32%" border="0" cellspacing="0" cellpadding="2" align="center" class="login">
                <tr align="center" valign="middle">
                  <td colspan="2" class="logintitle">Login</td>
                </tr>
                <tr align="left" valign="top">
                  <td colspan="2">
                    <div align="center" class="loginerror"> </div>
                  </td>
                </tr>
                <tr>
                  <td width="26%" valign="top" align="left" class="loginitem"> Login<br>
				      <input name="url" type="hidden" value="<?php echo $tpl['url']; ?>">
                      <input type="text" name="login" maxlength="1000" size="25" value="<?php echo $tpl['login']; ?>">
                  </td>
                  <td width="74%" valign="top" align="center"> </td>
                </tr>
                <tr>
                  <td width="26%" valign="top" align="left" class="loginitem"> Passwd<br>
                      <input type="password" name="password" size="25" maxlength="100">
                  </td>
                  <td width="74%" valign="middle" align="center" class="logintext"> </td>
                </tr>
                <tr>
                  <td valign="top" align="left" class="loginitem">Turing Key<br>
                      <input type="text" name="captcha_turing_key" value="" maxlength="5" size="25">
                      <input type="hidden" name="captcha_public_key" value="<?php echo $tpl['public_key']; ?>" maxlength="5" size="40">
                  </td>
                  <td valign="top" align="center" class="logintext"><span class="logintext"><img src="<?php echo $tpl['captcha_pic']; ?>" border="1"></span></td>
                </tr>
                <tr align="center">
                  <td width="26%" colspan="2" valign="middle"><br>
                      <input type="submit" name="dologin" value="login" onclick="subok(this.form.dologin);" class="loginbutton">
                  </td>
                </tr>
              </table>
            </form>
</td>
         </tr>
        </table>
       </td>
      </tr>
      <tr valign="middle" bgcolor="#516570">
        <td colspan="2" align="left" class="footer">
        
        <!-- --- Footer text --- -->
        <?php echo $tpl['footer']['body']; ?>        
        </td>
        </tr>
    </table>
   </td>
  </tr>
</table>
</body>
</html>
