<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="robots" content="index">
<meta name="description" content="<?php echo str_replace("\"","'",$B->sys['option']['site_desc']); ?>">
<meta name="keywords" content="<?php echo str_replace("\"","'",$B->sys['option']['site_desc']); ?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->sys['option']['charset']; ?>">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo htmlspecialchars($B->sys['option']['site_title']); ?></title>
</head>

<body>
<p><a href="admin/index.php">admin</a></p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">This page is produced
      by the test_showvars-tpl.php template. You will find the template of this
      page in the root folder <strong>/test_showvars-tpl.php</strong>
</font></p>
<p align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="index.php">
back to main page</a></font></p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">The following
    system array
($B->sys) contains vars which are always accessible in any template. Example
to
access
a
single variable from this array:<br />
<strong>Charset = <?php echo $B->sys['option']['charset']; ?></strong></font></p>
<p>Here is the complete array structure:<br /><strong><pre><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php print_r($B->sys); ?></font></pre></strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
</p>

</font>
</body>
</html>
