<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PHP Framework</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->sys['option']['charset']; ?>" />
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
.style1 {
    font-size: 14px;
    font-weight: bold;
    color: #990000;
}
.style2 {
    font-size: 14px;
    color: #333333;
}
.style3 {font-size: 14px}
-->
</style>
</head>

<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#00CCCC"><span class="style1"> smartFrame PHP Framework </span></td>
  </tr>
  <tr>
    <td align="left" valign="top"><table width="100%"  border="0" cellspacing="5" cellpadding="5">
      <tr>
        <td align="left" valign="top">
          <p align="right" class="style2"> <a href="admin/index.php">Admin</a></p>
          <p class="style2">Test Public modules features:</p>
          <ul>
            <li><a href="index.php?tpl=earchivemain" class="style2">Mailarchiver</a> </li>
          </ul>          <p class="style2">&nbsp;</p></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php $B->M( MOD_MAILARCHIVER, 
             MAILARCHIVER_GET_LISTS, 
             array('var' => 'test', 'fields' => array('lid','name'))); ?>            
</body>
</html>