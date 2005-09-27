<!-- --- prevent direct all --- -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>

<!-- --- charset setting --- -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<meta name="keywords" content="">

<!-- --- output sitemap navigation node title --- -->
<title>SMART3 - <?php echo $tpl['node']['title'];  ?></title>

<!-- --- use allways the php relative path definition to include media files --- -->
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/smart.css" rel="stylesheet" type="text/css">
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/typography.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.nodelevel0 {
  font-size: 18px;
  font-weight: bold;
  padding-top: 10px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
}
.nodelevel1 {
  font-size: 16px;
  font-weight: bold;
  padding-top: 8px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
}
.nodelevel2 {
  font-size: 14px;
  font-weight: 500;
  padding-top: 6px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
}
.nodelevel3 {
  font-size: 12px;
  font-weight: 500;
  padding-top: 4px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
}
.nodelevel4 , .nodelevel5 , .nodelevel6{
  font-size: 10px;
  font-weight: 500;
  padding-top: 2px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
}
.articlelevel {
  font-size: 10px;
  font-weight: normal;
  padding-top: 2px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-left: 5px;
}
-->
</style>
</head>

<body>

<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="maintab">
  <tr>
    <td>
    
    <!-- --- include header view --- -->
    <?php $viewLoader->header();?></td>
  </tr>
  <tr>
    <td><table width="760" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="120" align="left" valign="top" bgcolor="#2096DB" class="leftcol">
            
            <!-- --- include main navigation menu links view --- -->
            <?php $viewLoader->mainNavigation();?>  
        </td>
        <td width="638" align="left" valign="top"><table width="638" border="0" cellspacing="4" cellpadding="2">
          <tr>
            <td>
           
           <!-- --- print title and body of the sitemap navigation node --- -->
           <h3><?php echo $tpl['node']['title'];  ?></h3>
           <?php if(!empty($tpl['node']['body'])): ?>
             <div class="text"><?php echo $tpl['node']['body'];  ?></div>
           <?php endif; ?>  
           
           <!-- --- show the whole navigation node tree (sitemap) --- -->
           <div class="sitemap">
           <?php foreach($tpl['tree'] as $node):  ?>
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
               <td width="1%" align="left" valign="top"><?php echo str_repeat('&nbsp;&nbsp;',$node['level'] * 2); ?></td>
               <td width="99%" align="left" valign="top" class="nodelevel<?php echo $node['level']; ?>">
                 -<a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a>        
           <?php foreach($node['article'] as $article): ?>
             <div class="articlelevel">* <a href="<?php echo SMART_CONTROLLER; ?>?id_article=<?php echo $article['id_article']; ?>&view=article"><?php echo $article['title']; ?></a></div>
           <?php endforeach; ?>
         </td>
              </tr>
             </table>
           <?php endforeach; ?>  
           </div> 
         </td>
         </tr>
        </table>
       </td>
      </tr>
      <tr valign="middle" bgcolor="#516570">
        <td colspan="2" align="left" class="footer">
        
        <!-- --- Footer text --- -->
        <?php echo $tpl['footer']['body']; ?>        
        </td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
