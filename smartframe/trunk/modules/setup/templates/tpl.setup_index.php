<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Smart Frame Setup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
<!--
body,td,th {
    font-family: Verdana, Arial, Helvetica, sans-serif;
}
body {
    margin-left: 0px;
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0px;
}
.title {
  color: #CCFF66;
  font-weight: bold;
  font-size: 14px;
}
.subtitle {
  font-size: 12px;
  color: #990000;
  font-weight: bold;
}
.normal {font-size: 12px}
.subtitle {
    font-size: 12px;
    color: #990000;
    font-weight: bold;
}
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
<?php if(count($tpl['setup_error'])> 0): ?>
    <div><?php foreach($tpl['setup_error'] as $err) echo $err . '<br />'; ?></div>
<?php endif; ?>
<form name="setup" id="setup" method="post" action="<?php echo SMART_CONTROLLER; ?>">
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#3366CC"><span class="title">Setup </span></td>
  </tr>
  <tr>
    <td align="center" valign="top"><table width="90%"  border="0" cellspacing="4" cellpadding="2">
      <tr bgcolor="#CCCCCC">
        <td colspan="2" align="left" valign="top"><span class="subtitle">&nbsp;Set Charset of your site</span></td>
      </tr>
      <tr>
        <td width="19%" align="left" valign="top"><span class="normal">Charset:</span></td>
        <td width="81%" align="left" valign="top"><select name="charset">
          <option value="iso-8859-1" selected="selected">Western (iso-8859-1)</option>
          <option value="iso-8859-15">Western (iso-8859-15)</option>
          <option value="iso-8859-2">Central European (iso-8859-2)</option>
          <option value="iso-8859-3">South European (iso-8859-3)</option>
          <option value="iso-8859-4">Baltic (iso-8859-4)</option>
          <option value="iso-8859-13">Baltic (iso-8859-13)</option>
          <option value="iso-8859-5">Cyrillic (iso-8859-5)</option>
          <option value="iso-8859-6" disabled>Arabic (iso-8859-6)</option>
          <option value="iso-8859-7">Greek (iso-8859-7)</option>
          <option value="iso-8859-8" disabled>Hebrew (iso-8859-8)</option>
          <option value="iso-8859-9">Turkish (iso-8859-9)</option>
          <option value="iso-8859-10">Nordic (iso-8859-10)</option>
          <option value="iso-8859-11">Thai (iso-8859-11)</option>
          <option value="iso-8859-14">Celtic (iso-8859-14)</option>
          <option value="iso-8859-16">Romanian (iso-8859-16)</option>
          <option value="utf-8" disabled>Unicode (utf-8)</option>
        </select>
        </td>
      </tr>
    </table>
    <?php if(!isset($tpl['dbtype']) || ($tpl['dbtype'] != 'sqlite')): ?>    
    <table width="90%"  border="0" cellspacing="4" cellpadding="2">
      <tr bgcolor="#CCCCCC">
        <td colspan="2" align="left" valign="top"><p><span class="subtitle">&nbsp;Database connect data</span></p>
        </td>
      </tr>
      <tr>
        <td width="19%" align="left" valign="top"><span class="normal">Host:</span></td>
        <td width="81%" align="left" valign="top">
          <input name="dbhost" type="text" size="50" maxlength="255" value="<?php if(isset($tpl['form_dbhost'])) echo $tpl['form_dbhost']; ?>"/>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">User:</span></td>
        <td align="left" valign="top">
          <input name="dbuser" type="text" size="50" maxlength="255"  value="<?php if(isset($tpl['form_dbuser'])) echo $tpl['form_dbuser']; ?>"/>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="normal">Password:</td>
        <td align="left" valign="top"><input name="dbpasswd" type="password" id="login" size="50" maxlength="255"  value="<?php if(isset($tpl['form_dbpasswd'])) echo $tpl['form_dbpasswd'] ?>"/></td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">DB Name :</span></td>
        <td align="left" valign="top">
          <input name="dbname" type="text" size="50" maxlength="255" value="<?php if(isset($tpl['form_dbname'])) echo $tpl['form_dbname'] ?>"/> 
          </td>      
      <tr>
        <td align="left" valign="top"><span class="normal">Tables Prefix :</span></td>
        <td align="left" valign="top">
          <input name="dbtablesprefix" type="text" size="50" maxlength="255" value="<?php if(empty($tpl['form_dbtableprefix'])) echo 'smart_';else echo $tpl['form_dbtableprefix']; ?>"/>
        </td>
      </tr>
    </table>
    <?php endif; ?>
    <table width="90%"  border="0" cellspacing="4" cellpadding="2">
      <tr bgcolor="#CCCCCC">
        <td colspan="2" align="left" valign="top"><span class="subtitle">&nbsp;Create an editor login</span></td>
      </tr>
      <tr>
        <td width="19%" align="left" valign="top"><span class="normal">Name:</span></td>
        <td width="81%" align="left" valign="top">
          <input name="sysname" type="text" size="50" maxlength="255" value="<?php if(isset($tpl['form_sysname'])) echo $tpl['form_sysname']; ?>"/>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">Lastname:</span></td>
        <td align="left" valign="top">
          <input name="syslastname" type="text" size="50" maxlength="255" value="<?php if(isset($tpl['form_syslastname'])) echo $tpl['form_syslastname']; ?>"/>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="normal">Login:</td>
        <td align="left" valign="top"><input name="syslogin" type="text" id="login" size="50" maxlength="255" value="<?php if(isset($tpl['form_syslogin'])) echo $tpl['form_syslogin']; ?>"/></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="normal">Email:</td>
        <td align="left" valign="top"><input name="sysemail" type="text" id="email" size="50" maxlength="255" value="<?php if(isset($tpl['form_sysemail'])) echo $tpl['form_sysemail']; ?>"/></td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">Password:</span></td>
        <td align="left" valign="top">
          <input name="syspassword1" type="password" size="50" maxlength="255" value="<?php if(isset($tpl['form_syspassword1'])) echo $tpl['form_syspassword1'] ?>"/>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">Retype password: </span></td>
        <td align="left" valign="top"> <input name="syspassword2" type="password" size="50" maxlength="255" value="<?php if(isset($tpl['form_syspassword2'])) echo $tpl['form_syspassword2'] ?>"/></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top"><p>&nbsp;
      </p>
      <p>
        <input type="submit" name="do_setup" value="Submit" />
      </p></td>
  </tr>
</table>
</form>
</body>
</html>