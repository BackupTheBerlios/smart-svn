<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?>   
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
  <table width="350" border="0" cellspacing="0" cellpadding="2" align="center" class="login">
    <tr align="center" valign="middle">
      <td width="74%" class="logintitle">User Validation</td>
    </tr>
    <tr align="left" valign="top"> 
      <td> 
        <div align="center" class="loginerror"><?php echo $B->tpl_validation_message; ?></div>
    </td>
  </tr>
  <?php if($B->tpl_is_valid === TRUE): ?>
  <tr align="left">
    <td align="center" valign="middle" class="loginitem"><a href="index.php">Procceed</a> </td>
  </tr>
  <?php endif; ?>
</table>
</form>
</body>
</html>