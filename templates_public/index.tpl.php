<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PHP Framework</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->charset; ?>" />
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
    <td align="center" valign="middle" bgcolor="#00CCCC"><span class="style1"> Proposal for a PHP Framework </span></td>
  </tr>
  <tr>
    <td align="left" valign="top"><table width="100%"  border="0" cellspacing="5" cellpadding="5">
      <tr>
        <td align="left" valign="top"><p class="style2">Framework based on <a href="http://www.php-tools.de">php tools</a> .</p>
          <p class="style2"> go to the <a href="admin/index.php">admin</a> section.</p>
          <p class="style2">&nbsp;</p>          <p><strong>How it works? </strong></p>
          <p class="style3">The system is split into a public (this page) and a private (admin) part. </p>
          <p class="style3">The administration is built on a module-based structure. The core system and the modules are grouped around an event distributor which consists on methods of the class in <strong>/admin/include/event.php</strong>. This distributor receives event messages and distributes them to the destinations (event handlers) which could be the modules or the system it self. Each module has an event handler, which receives events and reacts on them. </p>
          <p class="style3"><strong>The Core system </strong></p>
          <p class="style3">/admin/include/base.admin.inc.php </p>
          <p class="style3">The core system includes patTemplate, patConfigurator, patErrorManager and some useful base classes. It also registers the event handlers of the modules. </p>
          <p class="style3">&nbsp;</p>
          <p class="style3"><strong>The main admin file </strong> admin/index.php. </p>
          <p class="style3">It produces a user authentication event, a init event, a possible logout event and a event to load the demanded module. </p>          <p class="style3">&nbsp;</p>          <p class="style2">&nbsp;</p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>