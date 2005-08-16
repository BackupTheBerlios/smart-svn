<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<meta name="keywords" content="php,framework,module,scalable,flexible,mvc,application,design,Armand,Turpel,Luxembourg">
<title>SMART3 PHP Framework - Scalable Application Design</title>
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/smart.css" rel="stylesheet" type="text/css">
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/typography.css" rel="stylesheet" type="text/css">
</head>

<body>

<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="maintab">
  <tr>
    <td><!-- include header view -->
        <?php $viewLoader->header();?></td>
  </tr>
  <tr>
    <td><table width="760" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="120" align="left" valign="top" bgcolor="#2096DB" class="leftcol">
            <!-- include main navigation menu links view -->
            <?php $viewLoader->mainNavigation();?>
    </td>
        <td width="638" align="left" valign="top"><table width="638" border="0" cellspacing="4" cellpadding="2">
          <tr>
            <td align="left" valign="top">
			<table width="97" border="0" align="right" cellpadding="2" cellspacing="2">
              <tr>
                <td width="89" align="left" valign="top" class="font12">
				<?php if($tpl['lang']=='en'): ?><a href="?lang=de">deutsche Version</a><?php else: ?><a href="?lang=en">english version</a><?php endif; ?>
				</td>
              </tr>
            </table>
              <!-- Page main text -->
              <?php echo $tpl['text']['body']; ?>
              </td>
          </tr>
        </table>
          </td>
      </tr>
      <tr valign="middle" bgcolor="#516570">
        <td colspan="2" align="left" class="footer">
        <!-- Footer text -->
        <?php echo $tpl['footer']['body']; ?>
        </td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
