<?php /* list Template. See also /view/class.view_list.php */ ?>

<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="index">
<meta name="description" content="<?php echo str_replace("\"","'",$B->sys['option']['site_desc']); ?>" />
<meta name="keywords" content="<?php echo str_replace("\"","'",$B->sys['option']['site_desc']); ?>" />
<title><?php echo htmlspecialchars($B->sys['option']['site_title']); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->sys['option']['charset']; ?>" />
<link href="<?php echo SF_RELATIVE_PATH; ?>media/earchive.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
function go(x){
    if(x != ""){
    window.location.href = x;
    }
}
</script>
</head>

<body>
<table width="102%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="760" bgcolor="#0066FF">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="3%"><img src="<?php echo SF_RELATIVE_PATH; ?>media/logo.gif" width="760" height="64" /></td>
                </tr>
            </table></td>
        <td width="44" bgcolor="#0066FF">&nbsp;</td>
    </tr>
    <tr>
        <td align="left" valign="middle" bgcolor="#333333"><table width="760"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="21%"><img src="<?php echo SF_RELATIVE_PATH; ?>media/empty.gif" alt="" name="empty" width="8" height="25" id="empty" /></td>
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
                       <form name="mode" id="mode" method="post" action="">
                        <td width="30%" align="right" valign="middle">
                          <font color="#99CCFF" size="1">view &gt; 
                          </font>
                          <select name="mode" class="searchform" onChange="go('<?php echo SF_CONTROLLER; ?>?view=list&lid=<?php echo $_REQUEST['lid']; ?>&mode='+this.form.mode.options[this.form.mode.options.selectedIndex].value)">
                          <option value="flat" <?php echo $B->tpl_select_flat; ?>>Flat</option>
                          <option value="tree" <?php echo $B->tpl_select_tree; ?>>Tree</option>
                          </select>
                        </td>
                       </form>
                       <form name="esearch" id="esearch" method="post" action="<?php echo SF_CONTROLLER; ?>?view=search">
                         <td width="30%" align="right" valign="middle">
                           <input name="search" type="text" value="<?php if($_POST['search']) echo htmlspecialchars(stripslashes($_POST['search'])); else echo "search"; ?>" size="25" maxlength="128" class="searchform" /></td>
                       </form>
                    </tr>
                </table></td>
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
                <td width="81%" align="left" valign="top">
                <table width="100%"  border="0" cellspacing="2" cellpadding="0">
                    <tr>
                        <td align="left" valign="top">
                           <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <?php //show top pager links  ?>
                            <?php if(!empty($B->tpl_prevnext)): ?>
                            <tr>
                                <td align="left" valign="top">
                                  <div class='pager'><?php echo $B->tpl_prevnext; ?></div>                              </td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">
                                  <hr/>
                                </td>
                            </tr>  
                            <?php endif; ?>
                            <tr>
                                <td align="left" valign="top">
                                  <?php //show messages  ?>
                                  <?php if (count($B->tpl_msg) > 0): ?>
                                    <?php foreach($B->tpl_msg as $msg): ?>
                                      <?php if( ($B->tpl_mode=='tree') && ($msg['level'] == 1) ): ?>
                                      <table width="100%">
                                      <tr><td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="96%" align="left" valign="top">
                                      <?php endif; ?>
                                      <div class='msgdate'>DATE: <?php echo $msg['mdate']; ?></div>
                                      <div class='msgfrom'>FROM: <?php echo $msg['sender']; ?></div>
                                      <a href="<?php echo SF_CONTROLLER; ?>?view=message&mid=<?php echo $msg['mid']; ?>&lid=<?php echo $msg['lid']; ?>&pageID=<?php echo $_GET['pageID']; ?>&mode=<?php echo $_REQUEST['mode']; ?>" class="msgtitle"><?php echo $msg['subject']; ?></a>
                                      <br />
                                      <br />
                                     <?php if( ($B->tpl_mode=='tree') && ($msg['level'] == 1) ): ?>
                                      </td></tr>
                                  </table>
                                      <?php endif; ?>                                      
                                    <?php endforeach; ?>
                                  <?php else: ?>
                                      <div class='pager'>Currently no messages available</div>
                                  <?php endif; ?>                               </td>
                            </tr>
                            <?php //show down pager links  ?>
                            <?php if(!empty($B->tpl_prevnext)): ?>
                            <tr>
                                <td align="left" valign="top">
                                  <hr/>
                                </td>
                            </tr>                               
                            <tr>
                                <td align="left" valign="top">
                                  <div class='pager'><?php echo $B->tpl_prevnext; ?></div>
                                </td>
                            </tr>
                            <?php endif; ?>
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
