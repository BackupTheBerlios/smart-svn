<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="keywords" content="<?php echo $B->sys['option']['site_desc']; ?>">
<title><?php echo $B->sys['option']['site_title']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="templates_smart/smart.css" rel="stylesheet" type="text/css">
</head>

<body topmargin="0">
<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="maintab">
  <tr>
    <td><a href="index.php"><img src="templates_smart/smart-banner.png" width="760" height="100" border="0"></a></td>
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
            <td align="left" valign="top">
			  <h3>SMART?</h3>
              <p class="text">It is a simple PHP script for developers to modularize their code and so, keep it clean, well structured and scalable. The base SMART Framework package size (without any module) is small (~40kb). SMART is still alpha stuff!</p>
              <p class="text">It wasnt the goal to build a framework, which follows the MVC pattern. Nervertheless you can identify this pattern in SMART. The <a href="http://wact.sourceforge.net/index.php/ModelViewController" target="_blank">ModelViewController</a> outline is there.</p>
              <p class="text">The base framework has no build in high level functionalities. It only manage modules. An application based on this framework become alive through modules. &quot;Little Jo&quot;, an example application gives you an impression what this means. This site is build up on &quot;little Jo&quot;.</p>
              <p class="text">SMART has a build in templates support, which was never planned but, which inevitable rose from the internal logic of SMART. You can control the templates presentation behaviour through view classes, which retrive the needed data from different modules through event calls. The template language is <a href="http://www.php.net/" target="_blank">PHP</a>.</p>
              <p class="text">If your are not convenient with the build in template engine you can use your prefered engine. This count also for a database layer. SMART isnt fixed to a specific DB layer. This is the choice of the module designer to include the required layer.</p>
              <p class="text">Other build in support: error handling.</p>
              <p class="text">SMART is released under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GPL</a>.</p>
              <p class="text">The SMART source code is hosted on <a href="http://developer.berlios.de/projects/smart/" target="_blank">Berlios</a> under the Subversion control.</p></td>
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
