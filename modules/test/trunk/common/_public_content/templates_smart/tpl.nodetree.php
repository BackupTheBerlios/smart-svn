<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="keywords" content="<?php echo $B->sys['option']['site_desc']; ?>">
<title><?php echo $B->sys['option']['site_title']; ?> - Sitemap ---</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="templates_smart/smart.css" rel="stylesheet" type="text/css">
</head>

<body topmargin="0">
<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="maintab">
  <tr>
    <td><img src="templates_smart/smart-banner.png" width="760" height="100"></td>
  </tr>
  <tr>
    <td><table width="760" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="120" align="left" valign="top" bgcolor="#2096DB" class="leftcol">
            <?php /* ### include the navigation menu view (template) ### */ ?>
            <?php M( MOD_SYSTEM, 'get_view', array('view' => 'navigation')); ?>		
		</td>
        <td width="640" align="left" valign="top"><table width="640" border="0" cellspacing="4" cellpadding="2">
          <tr>
            <td>
              <h3>Sitemap </h3>
              <hr class="hr" />
			  <div class="text sitemap">
              <?php foreach($B->tpl_tree as $val):  ?>
              <?php echo str_repeat('-&nbsp;',$val['level'] * 3); ?><a href="<?php echo SF_CONTROLLER; ?>?view=node&node=<?php echo $val['node']; ?>"><?php echo $val['title']; ?></a><br />
              <br />
              <?php endforeach; ?></div>
            </td>
          </tr>
        </table>
          </td>
      </tr>
      <tr valign="middle" bgcolor="#516570">
        <td colspan="2" align="left" class="footer"><?php include_once("templates_smart/tpl.footer.php"); ?>
        </td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
