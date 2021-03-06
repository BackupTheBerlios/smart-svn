<!-- prevent direct all -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<!-- --- charset setting --- -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<meta name="keywords" content="">

<!-- --- AJAX --- -->
<script type='text/javascript' src='ajaxserver.php?client=all&amp;stub=all&amp;view=articleAjax'></script>
<script type='text/javascript' src='templates_smart/ArticleAjax.js'></script>

<!-- --- output current navigation node title --- -->
<title>SMART3 - <?php echo $tpl['article']['title'];  ?></title>
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
</head>

<body>

<table width="760" border="0" cellpadding="0" cellspacing="0" class="maintab">
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
           <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $tpl['node']['id_node']; ?>"><?php echo $tpl['node']['title']; ?></a>
                     <hr class="hr" />
                    </div>             
           </td>
                 </tr>
                 <tr>
                   <td align="left" valign="top">
                         <!-- print title and body of a navigation node -->
             <?php if(!empty($tpl['article']['overtitle'])): ?>
                <div class="overtitle"><?php echo $tpl['article']['overtitle'];  ?></div>
             <?php endif; ?>
                         <h3><?php echo $tpl['article']['title'];  ?></h3>
             <?php if(!empty($tpl['article']['subtitle'])): ?>
                <div class="subtitle"><?php echo $tpl['article']['subtitle'];  ?></div>
             <?php endif; ?>
             <?php if(!empty($tpl['article']['header'])): ?>
                <div class="header"><?php echo $tpl['article']['header'];  ?></div>
             <?php endif; ?>
             <div class="body"><?php echo $tpl['article']['body'];  ?></div>
             <?php if(!empty($tpl['article']['ps'])): ?>
                <div class="ps"><?php echo $tpl['article']['ps'];  ?></div>
             <?php endif; ?>
             
             <!-- ########### AJAX Examples ############ -->
             
             <!-- AJAX  simple text -->
             <input type="button" name="simpletext" value="show text" onclick="remoteTest.simpleText(); return false;">
             <div id="simpletext"></div>             
             <p>&nbsp;</p>
             
             <!-- AJAX  show alert box -->
             <input type="button" name="showalertbox" value="show alert box" onclick="remoteTest.showAlertBox(); return false;">
             <p>&nbsp;</p>
             
             <!-- AJAX  calculator -->
             <h4>Calculator</h4>
             <input name="number1" id="number1" type="text" size="8" maxlength="8">
             +
             <input name="number2" id="number2" type="text" size="8" maxlength="8">
             <input type="button" name="calculate" value="=" onclick="doCalculation(); return false;">
             <span id="result"></span>    
             <p></p>
             
             <!-- AJAX  article search -->
             <h4>Article search</h4>
             <input name="search" id="search" type="text" size="35" maxlength="35">
             <input type="button" name="dosearch"  id="dosearch" value="search" onclick="doSearch(); return false;">
             <div id="searchresult"></div>                    
             <!-- ########### END AJAX Examples ############ -->  
             <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
                         <!-- --- show navigation node related files for download --- -->
                         <?php if(count($tpl['articleFiles'])>0): ?>
                         <br /><br />
             <div class="downloads">Downloads:</div>
                         <?php foreach($tpl['articleFiles'] as $file): ?>
                         <table width="350" border="0" cellspacing="2" cellpadding="2">
                           <tr>
                             <td width="10" align="left" valign="top" class="filelink">-</td>
                             <td width="340" align="left" valign="top" class="filelink"><a href="<?php echo SMART_RELATIVE_PATH; ?>data/article/<?php echo $tpl['article']['media_folder']; ?>/<?php echo $file['file']; ?>">
                               <?php if(!empty($file['title'])){echo $file['title'];}else{echo $file['file'];} ?>
                             </a></td>
                           </tr>
                           <?php if(!empty($file['description'])): ?>
                           <tr>
                             <td align="left" valign="top" class="filedesc"></td>
                             <td align="left" valign="top" class="filedesc"><?php echo $file['description']; ?></td>
                           </tr>
                           <?php endif; ?>
                         </table>
                         <?php endforeach; ?>
                         <?php endif; ?>
                         <!-- --- show keyword related article links --- -->
                         <?php if(count($tpl['keywordArticle'])>0): ?>
                         <br /><br />
             <div class="downloads">See also in keywords related articles:</div>
                         <?php foreach($tpl['keywordArticle'] as $key_article): ?>
                         <table width="350" border="0" cellspacing="2" cellpadding="2">
                           <tr>
                             <td width="10" align="left" valign="top" class="filelink">-</td>
                             <td width="340" align="left" valign="top" class="filelink"><a href="<?php echo SMART_CONTROLLER; ?>?view=article&id_article=<?php echo $key_article['id_article']; ?>"><?php echo $key_article['title']; ?></a></td>
                           </tr>
                           <?php if(!empty($key_article['description'])): ?>
                           <tr>
                             <td align="left" valign="top" class="filedesc">&nbsp;</td>
                             <td align="left" valign="top" class="filedesc"><?php echo $key_article['description']; ?></td>
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
                         <table width="350" border="0" cellspacing="2" cellpadding="2">
                           <tr>
                             <td width="10" align="left" valign="top" class="filelink">-</td>
                             <td width="340" align="left" valign="top" class="filelink"><a href="<?php echo $key_link['url']; ?>" target="_blank"><?php echo $key_link['title']; ?></a></td>
                           </tr>
                           <?php if(!empty($key_link['description'])): ?>
                           <tr>
                             <td align="left" valign="top" class="filedesc">&nbsp;</td>
                             <td align="left" valign="top" class="filedesc"><?php echo $key_link['description']; ?></td>
                           </tr>
                           <?php endif; ?>
                         </table>
                         <?php endforeach; ?>
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
