?php /* 
       Index template. It is loaded by default if no template is defined. 
       This template is a member of the unavailable group. You can define this group
       in the admin options menu.
     */?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?php /* 
        --------------------------------------------------------------
        Print out system variables defined in the admin options menu. 
        --------------------------------------------------------------*/?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->sys['option']['charset']; ?>">
<title><?php echo htmlspecialchars($B->sys['option']['site_title']); ?></title>
</head>

<body>
<p>This site is currently unavailable. Please come back later.<br><br>This page is produced by the "unavailable_index.tpl.php" template.<br><br><a href="admin/index.php">back to admin</a></p>
</body>
</html>
