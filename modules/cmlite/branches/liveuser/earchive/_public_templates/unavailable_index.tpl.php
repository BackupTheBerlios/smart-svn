<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?> 
<?php //get all available email lists and store the result in the array $B->list ?>
<?php $B->M( MOD_EARCHIVE, 
             EARCHIVE_LISTS, 
             array('var' => 'list', 'fields' => array('lid','name','email','description','status'))); ?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="index">
<meta name="description" content="<?php echo str_replace("\"","'",$B->sys['option']['site_desc']); ?>" />
<meta name="keywords" content="<?php echo str_replace("\"","'",$B->sys['option']['site_desc']); ?>" />
<title><?php echo htmlspecialchars($B->sys['option']['site_title']); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->sys['option']['charset']; ?>" />
<link href="media/earchive.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style4 {color: #0000FF}
-->
</style>
</head>

<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="760" bgcolor="#0066FF">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="3%"><img src="media/logo.gif" width="760" height="64" /></td>
                </tr>
            </table></td>
        <td bgcolor="#0066FF">&nbsp;</td>
    </tr>
    <tr>
        <td align="left" valign="middle" bgcolor="#333333">&nbsp;</td>
        <td bgcolor="#333333">&nbsp;</td>
    </tr>
    <tr>
        <td align="left" valign="top"><table width="75%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td>This site is currently unavailable. Please reload this page in
              a few moments.</td>
          </tr>
        </table></td>
        <td>&nbsp;</td>
    </tr>
    <tr valign="middle" bgcolor="#0066FF">
        <td colspan="2" align="left"><table width="100%"  border="0" cellspacing="0" cellpadding="8">
            <tr>
                <td><span class="footer">E-archive - &copy; 2004&nbsp;&nbsp;&nbsp; <a href="http://e-archive.open-publisher.net" target="_blank" class="footer">e-archive.open-publisher.net</a> </span></td>
            </tr>
        </table></td>
    </tr>   
</table>
</body>
</html>
