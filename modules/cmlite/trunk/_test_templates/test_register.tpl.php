<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?>   
<?php //check login data ?>
<?php $B->M( MOD_USER, 
             EVT_REGISTER, 
             array('register'    => $_POST['do_register'],
                   'var'         => 'tpl',
                   'error_var'   => 'error',
                   'success_var' => 'success',
                   'email_subject' => 'Your Smartframe registration',
                   'email_msg'     => 'You have to click on the link below to activate your account:<br /><br />(URL)<br /><br />Please contact the administrator on problems: (EMAIL).',
                   'reg_data' => array('login'    => $_POST['login'], 
                                       'passwd1'  => $_POST['passwd1'],
                                       'passwd2'  => $_POST['passwd2'],
                                       'forename' => $_POST['forename'],
                                       'lastname' => $_POST['lastname'],
                                       'email'    => $_POST['email']))); ?> 
<?php $B->M( MOD_USER, 
             EVT_VALIDATE, 
             array('error_var'   => 'v_error',
                   'error_msg'   => 'A unexpected error occured during your account validation. Please contact the administrator (EMAIL) or try again.',
                   'success_var' => 'v_success',
                   'success_msg' => 'Your registration validation was successfull. You can now use restricted content on site (URL)')); ?>
<html>
<head>
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">
<meta name="robots" content="noindex">
<title>Register</title>
<meta http-equiv="Content-Type" content="<?php echo $B->sys['option']['charset']; ?>">
<script language="JavaScript">
   function subok(s){
      s.value = "...wait";
   }
</script>
<style type="text/css">
<!--
.register {
    border: thin solid #0000CC;
    background-color: #CCCCCC;
}
.registerbody {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    color: #333333;
}
.registertitle {
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
.registeritem {
    font-size: 10px;
    font-weight: bold;
    color: #333333;
}
.registererror {
    font-size: 10px;
    font-weight: bold;
    color: #CC0000;
}
.registertext {
    font-size: 10px;
    color: #333333;
}
.registerbutton {
    font-size: 9px;
    color: #000066;
    background-color: #CCCC66;
    font-weight: bold;
    letter-spacing: 3px;
}
-->
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" class="registerbody">
<form name="form1" method="post" action="index.php?tpl=register">
  <table width="400" border="0" align="center" cellpadding="2" cellspacing="0" class="register">
    <tr align="center" valign="middle">
      <td colspan="2" class="registertitle">Register
       </td>
    </tr>
    
      <!-- if register success show thanks message else error message --> 
      <?php if ($B->error !== FALSE):  ?>  
       <tr align="center">
        <td colspan="2" valign="top" class="registererror">
                <?php echo $B->error; ?>
        </td>
       </tr>           
     <?php endif; ?>
     <tr>
       <td width="80%" align="left" valign="top" class="registeritem">
           Forename<br>
                     <input type="text" name="forename" value="<?php echo htmlentities($B->tpl['forename']); ?>" maxlength="1024" size="40">
       </td>
       <td width="80%" align="left" valign="top" class="registeritem">&nbsp;</td>
     </tr>
     <tr>
       <td width="80%" align="left" valign="top" class="registeritem">
           Lastname<br>
                     <input type="text" name="lastname" value="<?php echo htmlentities($B->tpl['lastname']); ?>" maxlength="1024" size="40">
       </td>
       <td width="80%" align="left" valign="top" class="registeritem">&nbsp;</td>
     </tr>
     <tr>
       <td width="80%" align="left" valign="top" class="registeritem">
           Login<br>
                     <input type="text" name="login" value="<?php echo htmlentities($B->tpl['login']); ?>" maxlength="1024" size="40">
       </td>
       <td width="80%" align="left" valign="top" class="registeritem">&nbsp;</td>
     </tr>
     <tr>
       <td width="80%" align="left" valign="top" class="registeritem">
           Password<br>
                     <input type="password" name="passwd1" value="" maxlength="1024" size="40">
       </td>
       <td width="80%" align="left" valign="top" class="registeritem">&nbsp;</td>
     </tr>
     <tr>
       <td width="80%" align="left" valign="top" class="registeritem">
           Retype password<br>
                     <input type="password" name="passwd2" value="" maxlength="1024" size="40">
       </td>
       <td width="80%" align="left" valign="top" class="registeritem">&nbsp;</td>
     </tr>
     <tr>
       <td width="80%" align="left" valign="top" class="registeritem">
           Email<br>
                     <input type="text" name="email" value="<?php echo htmlentities($B->tpl['email']); ?>" maxlength="1024" size="40">
       </td>
       <td width="80%" align="left" valign="top" class="registeritem">&nbsp;</td>
     </tr>
     <tr>
       <td align="left" valign="top" class="registeritem">
           Turing Key<br>
                     <input type="text" name="captcha_turing_key" value="" maxlength="5" size="40">
                                         <input type="hidden" name="captcha_public_key" value="<?php echo $B->captcha_public_key; ?>" maxlength="5" size="40">
             </td>
       <td align="left" valign="top" class="registeritem"><span class="logintext"><img src="<?php echo $B->captcha_turing_picture; ?>" border="1"></span></td>
     </tr>
     <tr align="center">
       <td colspan="2" valign="top" class="registeritem">Please enter the characters displayed in the picture . </td>
     </tr>      
     <tr align="center" valign="middle">
       <td width="80%" colspan="2"><br>
       <input type="submit" name="do_register" value="submit" onclick="subok(this.form.do_register);">       </td>
     </tr>
     <tr>
       <td colspan="2" class="registertext"><br /><b>INFO:</b><br /></td>
     </tr>
  </table>
</form>
</body>
</html>
