<!-- prevent direct call -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<!-- --- charset setting --- -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<meta name="keywords" content="">

<!-- --- output current navigation node title --- -->
<title>SMART3 - Search: <?php echo $tpl['search']; ?></title>

<!-- --- use allways the php relative path definition to include media files --- -->
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/smart.css" rel="stylesheet" type="text/css">
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/typography.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.article {
  font-size: 12px;
  font-weight: bold;
  color: #0000CC;
}  
a.search_pager {
	font-size: 14px;
}
span.search_pager {
	font-size: 14px;
	font-weight: bold;
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
            <td align="left" valign="top" width="2%" >
               <table width="100%" border="0" cellspacing="2" cellpadding="2">
                 <tr>
                   <td>
                    <!-- --- show search string --- -->
                    <div class="branch">Search: "<?php echo $tpl['search']; ?>"
                      <hr class="hr" />
                    </div>  				   
				   </td>
                 </tr>
                 <tr>
                   <td align="left" valign="top">
				   <!-- show pager links to other result pages -->
				   <?php if(!empty($tpl['pager'])): ?>
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                         <td width="190" align="left" valign="top">
						    <?php echo $tpl['pager']; ?>
                         </td>
                      </tr>
				   </table>
				   <hr>
				   <?php endif; ?>
				   <!-- show search result -->
				   <?php if(count($tpl['articles']) > 0): ?>
                         <table width="90%"  border="0" cellpadding="0" cellspacing="0">
                           <tr>
                             <td width="190" align="left" valign="top">
                               <ul class="subnodeul">
                                 <?php foreach($tpl['articles'] as $article): ?>
                                 <li class="subnodelist"> 
                                   <div class="branch">
                                    <?php  foreach($article['nodeBranch'] as $bnode): ?>
                                       <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
                                    <?php endforeach; ?>
					                <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $article['node']['id_node']; ?>"><?php echo $article['node']['title']; ?></a>
								   </div> 
								 <div class="article"><a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $article['id_node']; ?>&id_article=<?php echo $article['id_article']; ?>&view=article"><?php echo $article['title']; ?></a></div><br></li>
                                 <?php endforeach; ?>
                               </ul>
                             </td>
                           </tr>
                         </table>
				   <!-- show pager links to other result pages -->	 
				   <?php if(!empty($tpl['pager'])): ?>
				   <hr>
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                         <td width="190" align="left" valign="top">
						    <?php echo $tpl['pager']; ?>
                         </td>
                      </tr>
				   </table>
				   <?php endif; ?>						 
				   <?php else: ?>
				   no article found
				   <?php endif; ?>
				   </td>
                 </tr>
               </table>
               </td>
         </tr>
        </table>
          </td>
      </tr>
      <tr valign="middle" bgcolor="#516570">
        <td colspan="2" align="left" class="footer">
        
        <!-- --- show Footer text --- -->
        <?php echo $tpl['footer']['body']; ?>        
        </td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
