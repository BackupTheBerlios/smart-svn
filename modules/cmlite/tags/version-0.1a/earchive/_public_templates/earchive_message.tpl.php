<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?>  
<?php //get all available email lists and store the result in the array $B->tpl_list ?>
<?php $B->M( MOD_EARCHIVE, 
             EARCHIVE_LISTS, 
             array('var' => 'tpl_list', 'fields' => array('lid','name','email','description','status'))); ?> 
<?php //get the requested message and store the result in the array $B->tpl_msg ?>
<?php $B->M( MOD_EARCHIVE, 
             EARCHIVE_MESSAGE, 
             array('var'=>'tpl_msg', 'mid'=>(int)$_GET['mid'], 'lid'=>(int)$_GET['lid'], 'fields'=>array('subject','sender','mdate','body','folder'))); ?>                       
<?php //get the message attachments and store the result in the array $B->tpl_attach ?>
<?php $B->M( MOD_EARCHIVE, 
             EARCHIVE_MESSAGE_ATTACH, 
             array('var'=>'tpl_attach', 'mid'=>(int)$_GET['mid'], 'lid'=>(int)$_GET['lid'], 'fields'=>array('aid','mid','lid','file','size','type'))); ?>
<?php //Email obfuscation plugin  ?>
<?php include_once('plugins/function.mailto.php');  ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="index">
<meta name="description" content="<?php echo str_replace("\"","'",$B->tpl_msg['subject']); ?>" />
<meta name="keywords" content="<?php echo str_replace("\"","'",$B->tpl_msg['subject']); ?>" />
<title><?php echo htmlspecialchars($B->tpl_msg['subject']); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->sys['option']['charset']; ?>" />
<link href="media/earchive.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="102%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="760" bgcolor="#0066FF">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="3%"><img src="media/logo.gif" width="760" height="64" /></td>
                </tr>
            </table></td>
        <td width="44" bgcolor="#0066FF">&nbsp;</td>
    </tr>
    <tr>
        <td align="left" valign="middle" bgcolor="#333333"><table width="760"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="21%"><img src="media/empty.gif" alt="" name="empty" width="8" height="25" id="empty" /></td>
                <td width="79%" align="right" valign="middle"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="14%" align="left" valign="middle" class="topbar">
                            <?php //Show user name if the visitor is a registered user ?>
                            <?php if($B->auth->is_user): ?>
                              Hi,&nbsp;<?php echo $B->user_lastname.'&nbsp;'.$B->user_forename; ?>
                            <?php endif; ?>
                        </td>
                        <td width="26%" align="left" valign="middle">
                            <?php //show register link if allowed  ?>
                            <?php if((!$B->auth->is_user)&&($B->sys['option']['user']['allow_register']==TRUE)): ?>
                            <a href="index.php?tpl=register" class="topbarlink">register</a>
                            <?php endif; ?>
                        </td>
                        <td width="10%" align="left" valign="top">&nbsp;</td>
                        <form name="esearch" id="esearch" method="post" action="index.php?tpl=search">
                            <td width="50%" align="right" valign="middle">
                                <input name="search" type="text" value="<?php if($_POST['search']) echo htmlspecialchars(stripslashes($_POST['search'])); else echo "search"; ?>" size="25" maxlength="128" class="searchform" /></td>
                        </form>
                    </tr>
                </table>
               </td>
            </tr>
        </table></td>
        <td bgcolor="#333333">&nbsp;</td>
    </tr>
    <tr>
        <td height="249" align="left" valign="top"><table width="760"  border="0" cellspacing="2" cellpadding="2">
            <tr>
                <td width="19%" align="center" valign="top" class="vline">
                <table width="100%"  border="0" cellspacing="4" cellpadding="2">
                    <tr>
                        <td align="left" valign="top" class="leftnavlinks">
                        <?php if(!isset($_GET['tpl'])): ?>
                            <strong>Home</strong>
                        <?php else: ?>
                            <a href="index.php">Home</a>
                        <?php endif; ?>
                        </td>
                    </tr>                    
                    <tr>
                        <td align="left" valign="top" class="leftnavlinks">&nbsp;</td>
                    </tr>
                </table>
                    <table width="100%"  border="0" cellspacing="6" cellpadding="0">
                        <tr>
                            <td width="1%" colspan="2" align="left" valign="top"><span class="style3">E-archives</span></td>
                            </tr>
                          <?php //show available list links  ?>
                        <?php if (count($B->tpl_list) > 0): ?>
                            <?php foreach($B->tpl_list as $list): ?>                            
                                <tr>
                                    <td width="1%" align="left" valign="top" class="leftnavlinks">-</td>
                                    <td width="99%" align="left" valign="top" class="leftnavarchives">
                                        <?php if($list['lid']==$_GET['lid']): ?>
                                            <strong><?php echo $list['name'];  ?></strong>
                                        <?php else: ?>
                                            <a href="index.php?tpl=list&lid=<?php echo $list['lid']; ?>"><?php echo $list['name']; ?></a>
                                        <?php endif;  ?>
                                    </td>
                               </tr>
                           <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td width="1%" align="left" valign="top" class="leftnavlinks">-</td>
                            <td width="99%" align="left" valign="top" class="leftnavarchives">no archive</td>
                        </tr>                       
                        <?php endif; ?>     
                    </table></td>
                <td width="81%" align="left" valign="top"><table width="100%"  border="0" cellspacing="2" cellpadding="0">
                    <tr>
                        <td align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="left" valign="top">
                                  <div class='msgback'><a href="javascript:history.back();">Back</a></div>
                                  <?php if (count($B->tpl_msg) > 0): ?>
                                    <div class='msgdate'>DATE: <?php echo $B->tpl_msg['mdate']; ?></div>
                                    <div class='msgfrom'>FROM: <?php echo mailto($B->tpl_msg['sender']); ?></div>
                                    <div class="msgtitle"><?php echo $B->tpl_msg['subject']; ?></div>
                                    <div class="msgbody"><?php echo mailto($B->tpl_msg['body']); ?></div>
                                  <?php endif; ?>                               
                                 </td>
                            </tr>
                            <tr>
                              <td align="left" valign="top" class="msgbody">
                                 _______________________<br />
                                 <strong>Attachments of this message:</strong><br />
                                 <?php if (count($B->tpl_attach) > 0): ?>
                                   <?php foreach($B->tpl_attach as $attach): ?>
                                    <a href="index.php?tpl=attach&aid=<?php echo $attach['aid']; ?>&mid=<?php echo $attach['mid']; ?>&lid=<?php echo $attach['lid']; ?>"><?php echo stripslashes($attach['file']); ?></a>
                                    <div>Type: <?php echo $attach['type']; ?></div>
                                    <div>Size: <?php echo $attach['size']; ?></div>
                                    <br />
                                   <?php endforeach; ?>
                                 <?php else: ?>
                                    <div>No attachments for this message</div>
                                <?php endif; ?>                                 
                                </td>
                            </tr>                   
                        </table></td>
                    </tr>
                </table></td>
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
