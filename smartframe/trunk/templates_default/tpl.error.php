<?php /*
 ### Error template. It is loaded when a view class produce an error ### 
*/ ?>

<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Web user error page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><font size="5" face="Verdana, Arial, Helvetica, sans-serif"><strong>Web user error page</strong></font>
</p>
<p><font color="#990000" size="3" face="Verdana, Arial, Helvetica, sans-serif"><pre><?php print_r( $tpl ); ?></pre></font></p>
</body>
</html>
