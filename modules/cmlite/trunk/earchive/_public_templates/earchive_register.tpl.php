<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?>   
<?php //check login data ?>

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
<form name="form1" method="post" action="index.php?view=register&url=<?php echo $_GET['url'] ?>">
  <table width="400" border="0" align="center" cellpadding="2" cellspacing="0" class="register">
    <?php if(isset($B->tpl_v_error) || isset($B->tpl_v_success)): ?>
        <tr align="center" valign="middle">
        <?php if($B->tpl_v_success == TRUE): ?>
            <td colspan="2" class="registertitle">
            Your registration is now complete. You can access restricted content on site <a href="<?php echo $B->sys['option']['url']; ?>"><?php echo $B->sys['option']['url']; ?></a>
            </td>
    <?php endif; ?>
        <?php if($B->tpl_v_error == TRUE): ?>
            <td colspan="2" class="registererror">
            An error occured during your registration. Please contact the administrator <a href="mailto:<?php echo $B->sys['option']['email']; ?>"><?php echo $B->sys['option']['email']; ?></a> or try again.
            </td>
        <?php endif; ?>
        </tr>
    <?php else: ?>    
    <tr align="center" valign="middle">
      <td colspan="2" class="registertitle">Register
       </td>
    </tr>
      <!-- if register success show thanks message else error message --> 
      <?php if ($B->tpl_success === TRUE):  ?>  
       <tr align="center">
        <td colspan="2" valign="top" class="registererror">
      <?php if($B->sys['option']['user']['register_type'] == 'auto'): ?>
                Soon your will receive an email with further instructions to complete your account.
      <?php elseif($B->sys['option']['user']['register_type'] == 'manual'): ?>
        Your registration have to be validate by one of the adminstrators. You will receive an email message as soon as possible.
      <?php endif; ?>
        </td>
       </tr>  
      <?php elseif ($B->tpl_success === FALSE):  ?>  
       <tr align="center">
        <td colspan="2" valign="top" class="registererror">
                An error occured during your registration. Please contact the administrator <a href="mailto:<?php echo $B->sys['option']['email']; ?>"><?php echo $B->sys['option']['email']; ?></a> or try again.
        </td>
       </tr>        
      <?php endif; ?>   
      <!-- error message --> 
      <?php if ($B->tpl_error !== FALSE):  ?>  
       <tr align="center">
        <td colspan="2" valign="top" class="registererror">
                <?php echo $B->tpl_error; ?>
        </td>
       </tr>           
     <?php endif; ?>
     <tr>
       <td width="80%" align="left" valign="top" class="registeritem">
           Forename<br>
                     <input type="text" name="forename" value="<?php echo htmlentities($B->tpl_form['forename']); ?>" maxlength="1024" size="40">
       </td>
       <td width="80%" align="left" valign="top" class="registeritem">&nbsp;</td>
     </tr>
     <tr>
       <td width="80%" align="left" valign="top" class="registeritem">
           Lastname<br>
                     <input type="text" name="lastname" value="<?php echo htmlentities($B->tpl_form['lastname']); ?>" maxlength="1024" size="40">
       </td>
       <td width="80%" align="left" valign="top" class="registeritem">&nbsp;</td>
     </tr>
     <tr>
       <td width="80%" align="left" valign="top" class="registeritem">
           Login<br>
                     <input type="text" name="login" value="<?php echo htmlentities($B->tpl_form['login']); ?>" maxlength="1024" size="40">
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
                     <input type="text" name="email" value="<?php echo htmlentities($B->tpl_form['email']); ?>" maxlength="1024" size="40">
       </td>
       <td width="80%" align="left" valign="top" class="registeritem">&nbsp;</td>
     </tr>
     <tr>
       <td align="left" valign="top" class="registeritem">
           Turing Key<br>
                     <input type="text" name="captcha_turing_key" value="" maxlength="5" size="40">
                     <input type="hidden" name="captcha_public_key" value="<?php echo $B->tpl_public_key; ?>" maxlength="5" size="40">
             </td>
       <td align="left" valign="top" class="registeritem"><span class="logintext"><img src="<?php echo $B->tpl_captcha_pic; ?>" border="1"></span></td>
     </tr>
     <tr align="center">
       <td colspan="2" valign="top" class="registeritem">Please enter the characters displayed in the picture . </td>
     </tr>      
     <tr align="center" valign="middle">
       <td width="80%" colspan="2"><br>
       <input type="submit" name="do_register" value="submit" onclick="subok(this.form.do_register);">       </td>
     </tr>
     <?php endif; ?>
  </table>
</form>
</body>
</html>
