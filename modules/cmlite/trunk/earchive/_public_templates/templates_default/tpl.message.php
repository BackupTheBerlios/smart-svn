<?php /* message Template. See also /view/class.view_message.php */ ?>

<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="index">
<meta name="description" content="<?php echo str_replace("\"","'",$B->tpl_msg['subject']); ?>" />
<meta name="keywords" content="<?php echo str_replace("\"","'",$B->tpl_msg['subject']); ?>" />
<title><?php echo htmlspecialchars($B->tpl_msg['subject']); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->sys['option']['charset']; ?>" />
<link href="<?php echo SF_RELATIVE_PATH; ?>media_default/earchive.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="102%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="760" bgcolor="#0066FF">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="3%"><img src="<?php echo SF_RELATIVE_PATH; ?>media_default/logo.gif" width="760" height="64" /></td>
                </tr>
            </table></td>
        <td width="44" bgcolor="#0066FF">&nbsp;</td>
    </tr>
    <tr>
        <td align="left" valign="middle" bgcolor="#333333"><table width="760"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="21%"><img src="<?php echo SF_RELATIVE_PATH; ?>media_default/empty.gif" alt="" name="empty" width="8" height="25" id="empty" /></td>
                <td width="79%" align="right" valign="middle"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="14%" align="left" valign="middle" class="topbar">
                            <?php //Show user name if the visitor is a registered user ?>
                            <?php if($B->is_logged == TRUE): ?>
                              Hi,&nbsp;<?php echo $B->logged_user_lastname.'&nbsp;'.$B->logged_user_forename; ?>
                            <?php endif; ?>
                        </td>
                        <td width="26%" align="left" valign="middle">
                            <?php //show register link if allowed  ?>
                            <?php if( ($B->is_logged == FALSE) && ($B->sys['option']['user']['allow_register'] == TRUE) ): ?>
                            <a href="<?php echo SF_CONTROLLER; ?>?view=register" class="topbarlink">register</a>
                            <?php endif; ?>
                            <?php if( $B->is_logged == TRUE ): ?>
                            &nbsp;&nbsp;<a href="<?php echo SF_CONTROLLER; ?>?logout=1" class="topbarlink">logout</a>
                            <?php endif; ?>             
                        </td>
                        <td width="10%" align="left" valign="top">&nbsp;</td>
                        <form name="esearch" id="esearch" method="post" action="<?php echo SF_CONTROLLER; ?>?view=search">
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
                  <?php // include the navigation menu view (template)
                        M( MOD_SYSTEM, 'get_view', array('view' => 'navigation')); 
                  ?>
                </td>
                <td width="81%" align="left" valign="top"><table width="100%"  border="0" cellspacing="2" cellpadding="0">
                    <tr>
                        <td align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="left" valign="top">
                                  <div class='msgback'><a href="javascript:history.back();">Back</a></div>
                                  <?php if (count($B->tpl_msg) > 0): ?>
                                    <div class='msgdate'>DATE: <?php echo $B->tpl_msg['mdate']; ?></div>
                                    <div class='msgfrom'>FROM: <?php echo $B->tpl_msg['sender']; ?></div>
                                    <div class="msgtitle"><?php echo $B->tpl_msg['subject']; ?></div>
                                    <div class="msgbody"><?php echo $B->tpl_msg['body']; ?></div>
                                  <?php endif; ?>                               
                                 </td>
                            </tr>
                            <tr>
                              <td align="left" valign="top" class="msgbody">
                                 _______________________<br />
                                 <strong>Attachments of this message:</strong><br />
                                 <?php if (count($B->tpl_attach) > 0): ?>
                                   <?php foreach($B->tpl_attach as $attach): ?>
                                    <a href="<?php echo SF_CONTROLLER; ?>?view=attach&aid=<?php echo $attach['aid']; ?>&mid=<?php echo $attach['mid']; ?>&lid=<?php echo $attach['lid']; ?>"><?php echo stripslashes($attach['file']); ?></a>
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
