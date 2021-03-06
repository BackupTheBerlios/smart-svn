<!-- --- prevent direct all --- -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<!-- --- show picture title --- -->
<title>Picture - <?php echo $tpl['pic']['title']; ?></title>

<!-- --- charset setting --- -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<style type="text/css">
<!--
body {
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 14px;
  color: #0000CC;
  background-color: #FFFFFF;
  margin: 0px;
  padding: 0px;
}
-->
</style>
</head>

<body>

<!-- --- show table with picture and description --- -->
<table width="<?php echo $tpl['pic']['width']; ?>" border="0" align="center" cellpadding="0" cellspacing="5">
  <tr>
    <td align="left" valign="top"><h3><?php echo $tpl['pic']['title']; ?></h3></td>
  </tr>
  <tr>
    <td align="left" valign="top"><img name="<?php echo $tpl['pic']['title']; ?>" src="<?php echo SMART_RELATIVE_PATH; ?>data/<?php echo $tpl['module']; ?>/<?php echo $tpl['pic']['media_folder']; ?>/<?php echo $tpl['pic']['file']; ?>" alt="<?php echo $tpl['pic']['description'] ?>" width="<?php echo $tpl['pic']['width']; ?>" height="<?php echo $tpl['pic']['height']; ?>" title="<?php echo $tpl['pic']['title']; ?>"></td>
  </tr>
  <tr>
    <td align="left" valign="top"><p><?php echo $tpl['pic']['description']; ?></p></td>
  </tr>
</table>
</body>
</html>
