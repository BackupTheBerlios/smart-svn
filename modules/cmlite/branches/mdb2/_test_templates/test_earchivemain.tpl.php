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
.style4 {
	font-size: 18px;
	color: #CC0000;
	font-weight: bold;
}
.style5 {
  font-size: 14px;
	color: #3366CC;
	font-weight: bold;
}
.status {
  font-size: 10px;
	color: #000000;
}
.listdesc {
  font-size: 12px;
	color: #000000;
}
-->
</style>
</head>
<?php //get all available email lists and store the result in the array $B->list ?>
<?php $B->M( MOD_MAILARCHIVER, 
             MAILARCHIVER_LISTS, 
             array('var' => 'list', 'fields' => array('lid','name','email','description','status'))); ?> 
<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#00CCCC"><span class="style1"> SmartFrame</span></td>
  </tr>
  <tr>
    <td align="left" valign="top"><table width="100%"  border="0" cellspacing="5" cellpadding="5">
      <tr>
        <td align="left" valign="top">
          <p align="right" class="style2"> <a href="index.php">Main</a></p>
          <p class="style4">Mail archiver</p>
          <p class="style5">Available email archiv lists </p>
					<ul>
					<?php if (count($B->list) > 0): ?>
					    <?php foreach($B->list as $list): ?>
              <li><a href="index.php?tpl=earchivelist&lid=<?php echo $list['lid']; ?>" class="style2"><?php echo $list['name']; ?></a>
									    <?php if ($list['status']==2): ?>
											    <span class='status'>(public)</span>
											<?php elseif ($list['status']==3): ?>
											    <span class='status'>(restricted)</span>
											<?php endif; ?>
											<div class='listdesc'><?php echo $list['description']; ?></div>
							</li>
					    <?php endforeach; ?>
					<?php else: ?>
					    <div class='listdesc'>Currently no email list available</div>
					<?php endif; ?>
          </ul></td>
      </tr>
    </table></td>
  </tr>
</table>        
</body>
</html>