<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<meta name="keywords" content="php,framework,module,scalable,flexible,mvc,application,design,Armand,Turpel,Luxembourg">
<title>SMART3 PHP Framework - Scalable Application Design</title>
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/smart.css" rel="stylesheet" type="text/css">
</head>

<body topmargin="0">

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
              <?php echo $tpl['text'][0]['body']; ?>
              </td>
          </tr>
        </table>
          </td>
      </tr>
      <tr valign="middle" bgcolor="#516570">
        <td colspan="2" align="left" class="footer">&copy; 2005 Armand Turpel&nbsp;
        </td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
