<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?>   
<?php //check login data ?>
<?php $B->M( MOD_USER, 
             EVT_USER_LOGIN, 
             array('urlvar' => $_GET['ret'], 
                   'login'  => $_POST['login_name'], 
                   'passwd' => $_POST['password'])); ?> 
<html>
<head>
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">
<meta name="robots" content="noindex">
<title>Login</title>
<meta http-equiv="Content-Type" content="<?php echo $B->sys['option']['charset']; ?>">
<script language="JavaScript">
   function subok(s){
      s.value = "wait ...";
   }
</script>
<style type="text/css">
<!--
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
<body bgcolor="#FFFFFF" text="#000000" class="loginbody">
<form name="form1" method="post" action="index.php?view=login&url=<?php echo $_GET['url']; ?>">
  <table width="32%" border="0" cellspacing="0" cellpadding="2" align="center" class="login">
    <tr align="center" valign="middle">
      <td colspan="2" class="logintitle">Login</td>
    </tr>
    <tr align="left" valign="top"> 
      <td colspan="2"> 
        <div align="center" class="loginerror">
          </div>
    </td>
  </tr>
  <tr> 
      <td width="26%" valign="top" align="left" class="loginitem">
            Login<br>
            <input type="text" name="login_name" maxlength="1000" size="25"></td>
      <td width="74%" valign="top" align="left"> 
    </td>
  </tr>
  <tr> 
      <td width="26%" valign="top" align="left" class="loginitem">
            Passwd<br>
            <input type="password" name="password" size="25" maxlength="100"></td>
      <td width="74%" valign="top" align="center" class="logintext">
    </td>
  </tr>
  <tr align="center"> 
      <td width="26%" colspan="2" valign="middle"><br>
        <input type="submit" name="login" value="login" onclick="subok(this.form.login);" class="loginbutton">
      </td>
    </tr>
  <tr align="left">
    <td colspan="2" valign="middle" class="loginitem">
            <?php if($B->sys['option']['user']['allow_register'] == TRUE): ?>
                <a href="index.php?tpl=register">not yet registered?</a>
                <?php endif; ?></td>
  </tr>
</table>
</form>
</body>
</html>