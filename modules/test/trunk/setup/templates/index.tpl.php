<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Smart Frame Setup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="modules/setup/templates/setup.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.subtitle {
    font-size: 12px;
    color: #990000;
    font-weight: bold;
}
.normal {font-size: 12px}
.style1 {color: #990000; font-size: 12px;}
-->
</style>
</style>
<script language="JavaScript" type="text/JavaScript">
function go(x){
    if(x != ""){
    window.location.href = x;
    }
}
function subok(s){
    s.value = "... wait";
}
</script>
</head>
<body>
<!-- Display setup errors -->
<?php if(count($GLOBALS['B']->setup_error) > 0): ?>
    <?php foreach($GLOBALS['B']->setup_error as $error): ?>
        <?php echo $error; ?><br><br>
    <?php endforeach; ?>
<?php endif; ?>
<form name="setup" id="setup" method="post" action="index.php">
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#3366CC"><span class="title">Smart Frame Setup </span></td>
  </tr>
  <tr>
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top"><p>This template is loaded from the setup
        module: File 'index.tpl.php'. In must cases you will find here a database setup form
        (creating db tables) and a form to create an administrator user account. After submit
        this form the setup module will send setup event calls to all other modules. Each
        module is responsible for its own setup proceedure.</p>
      <p>
        <input type="submit" name="do_setup" value="Submit" />
      </p></td>
  </tr>
</table>
</form>
</body>
</html>