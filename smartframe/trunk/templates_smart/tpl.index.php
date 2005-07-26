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
        <h3 class="smart">SMART?</h3>
              <p class="smart">SMART is a <a href="http://www.php.net">PHP</a> tool, which helps developers to modularize their code and so, keep it clean, well structured and scalable. The base SMART Framework package size (without any module) is small (~40kb). SMART is still alpha stuff!</p>
              <p class="smart">It wasn't the goal to build a framework, which follows the MVC pattern. Nevertheless you can identify this pattern in SMART. The <a href="http://wact.sourceforge.net/index.php/ModelViewController" target="_blank">ModelViewController</a> outline is there.</p>
              <p class="smart">The base framework has no build in high level functionalities.  An application based on this framework becomes alive through modules. &quot;Little Jo&quot;, an example application gives you an impression of what this means. This site is build up on &quot;<a href="?view=node&node=-1028336057">little Jo</a>&quot;. It is also possible to build cli/cgi php applications.</p>
              <p class="smart">SMART has a build in templates support, which was never planned but, which inevitable rose from the internal logic of SMART. You can control the templates presentation behaviour through <em>view</em> classes, which retrieves the demanded data from different modules. The template language is <a href="http://www.php.net/" target="_blank">PHP</a>.</p>
              <p class="smart">In SMART a request is related to a view, which holds the contact to as many different module action classes as needed to proceed. There is a loose coupling between the view and the actions. The benefit of this mechanism is that it is easier to build a view, which interact with many different module items. In some MVC frameworks, solving such problems isn't evident.</p>
              <p class="smart">If your are not convenient with the build in template engine you can use your prefered engine. This also counts for a database layer. SMART isn't fixed to a specific DB layer. This is the choice of the module designer to include the required layer.</p>
              <p class="smart">Other build in support: error handling.</p>
              <p class="smart">SMART is released under the GNU LESSER GENERAL PUBLIC LICENSE.</p>
              <p class="smart">The SMART source code is hosted on <a href="http://developer.berlios.de/projects/smart/" target="_blank">Berlios</a> under the Subversion control.</p></td>
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
