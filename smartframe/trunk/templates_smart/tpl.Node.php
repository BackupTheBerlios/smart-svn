<!-- prevent direct all -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<!-- --- charset setting --- -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<meta name="keywords" content="">

<!-- --- output current navigation node title --- -->
<title>SMART3 - <?php echo $tpl['node']['title'];  ?></title>
<script language="JavaScript" type="text/JavaScript">
    function showimage(theURL,widthx,heightx){
        w = widthx+20;
        h = heightx+100;
        newwin= window.open(theURL,'image','width='+w+',height='+h+',dependent=no,directories=no,scrollbars=no,toolbar=no,menubar=no,location=no,resizable=yes,left=0,top=0,screenX=0,screenY=0'); 
} 
</script>

<!-- --- use allways the php relative path definition to include media files --- -->
<link href="<?php echo $tpl['relativePath']; ?>templates_smart/typography.css" rel="stylesheet" type="text/css">
<link href="<?php echo $tpl['relativePath']; ?>templates_smart/smart.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.filedesc {
  font-size: 12px;
  color: #2E2E2E;
}
.filelink {
  font-size: 12px;
}
.downloads {
  font-size: 12px;
  font-weight: bold;
  color: #0000CC;
}  
-->
</style>
<style type="text/css">
<!--
.unnamed1 {
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
                    <!-- --- show current navigation node branche --- -->
                    <div class="branch">Top /
                     <?php  foreach($tpl['nodeBranch'] as $bnode): ?>
                       <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
                     <?php endforeach; ?>
                     <hr class="hr" />
                    </div>             
           </td>
                 </tr>
                 <tr>
                   <td align="left" valign="top">
                   <table width="200" border="0" align="right" cellpadding="0" cellspacing="0">
                     <tr>
                       <td align="center" valign="top"><!-- --- show child nodes of the current navigation node --- -->
                         <?php if(count($tpl['childNodes']) > 0): ?>
                         <table width="200"  border="0" cellpadding="0" cellspacing="0" class="subnodetable">
                           <tr>
                             <td align="left" valign="top" class="subnodetitle">Subnodes</td>
                           </tr>
                           <tr>
                             <td width="190" align="left" valign="top">
                               <ul class="subnodeul">
                                 <?php foreach($tpl['childNodes'] as $cnode): ?>
                                 <li class="subnodelist"> <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $cnode['id_node']; ?>"><?php echo $cnode['title']; ?></a>
                                     <?php if(!empty($cnode['short_text'])): ?>
                                     <div class="font10"><?php echo $cnode['short_text']; ?></div>
                                     <?php endif; ?>
                                 </li>
                                 <?php endforeach; ?>
                               </ul>
                             </td>
                           </tr>
                         </table><br>
                         <?php endif; ?>
                         <!-- --- show child nodes of the current navigation node --- -->
                         <?php if(count($tpl['nodeArticles']) > 0): ?>
                         <table width="200"  border="0" cellpadding="0" cellspacing="0" class="subnodetable">
                           <tr>
                             <td align="left" valign="top" class="subnodetitle">Articles</td>
                           </tr>
                           <tr>
                             <td width="190" align="left" valign="top">
                               <ul class="subnodeul">
                                 <?php foreach($tpl['nodeArticles'] as $article): ?>
                                 <li class="subnodelist"> <a href="<?php echo SMART_CONTROLLER; ?>?id_article=<?php echo $article['id_article']; ?>&view=article"><?php echo $article['title']; ?></a> </li>
                                 <?php endforeach; ?>
                               </ul>
                             </td>
                           </tr>
               <?php if(!empty($tpl['pager'])): ?>
                           <tr>
                             <td width="190" align="center" valign="top">
                     <?php echo $tpl['pager']; ?>
                             </td>
                           </tr>
               <?php endif; ?>
                         </table>
                         <?php endif; ?>
                       </td>
                     </tr>
                   </table>
                         <!-- print title and body of a navigation node -->
                         <h3> <?php echo $tpl['node']['title'];  ?> </h3>
                         <span class="text"><?php echo $tpl['node']['body'];  ?></span>
                         <!-- --- show navigation node related files for download --- -->
                         <?php if(count($tpl['nodeFiles'])>0): ?>
                         <div class="downloads">Downloads:</div>
                         <?php foreach($tpl['nodeFiles'] as $file): ?>
                         <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                             <td class="filelink"><a href="<?php echo SMART_RELATIVE_PATH; ?>data/navigation/<?php echo $tpl['node']['media_folder']; ?>/<?php echo $file['file']; ?>">
                               <?php if(!empty($file['title'])){echo $file['title'];}else{echo $file['file'];} ?>
                             </a></td>
                           </tr>
                           <?php if(!empty($file['description'])): ?>
                           <tr>
                             <td class="filedesc"><?php echo $file['description']; ?></td>
                           </tr>
                           <?php endif; ?>
                         </table>
                         <?php endforeach; ?>
                         <?php endif; ?>
                         <!-- --- show keyword related links --- -->
                         <?php if(count($tpl['keywordLink'])>0): ?>
                         <br /><br />
						 <div class="downloads">See also keywords related links:</div>
                         <?php foreach($tpl['keywordLink'] as $key_link): ?>
                         <table width="100%" border="0" cellspacing="2" cellpadding="2">
                           <tr>
                             <td width="1%" align="left" valign="top" class="filelink">-</td>
                             <td width="99%" align="left" valign="top" class="filelink"><a href="<?php echo $key_link['url']; ?>" target="_blank"><?php echo $key_link['title']; ?></a></td>
                           </tr>
                           <?php if(!empty($key_link['description'])): ?>
                           <tr>
                             <td align="left" valign="top" class="filedesc">&nbsp;</td>
                             <td align="left" valign="top" class="filedesc"><?php echo $key_link['description']; ?></td>
                           </tr>
                           <?php endif; ?>
                         </table>
                         <?php endforeach; ?>
                         <?php endif; ?>						 </td>
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
