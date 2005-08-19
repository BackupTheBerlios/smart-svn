<!-- prevent direct all -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>ERROR</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><font size="5" face="Verdana, Arial, Helvetica, sans-serif"><strong>ERROR</strong></font>
</p>
<p><font color="#990000" size="3" face="Verdana, Arial, Helvetica, sans-serif"><pre><?php var_dump($tpl['message']); ?></pre></font></p>
</body>
</html>
