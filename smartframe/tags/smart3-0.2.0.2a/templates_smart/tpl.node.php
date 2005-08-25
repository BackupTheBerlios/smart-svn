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
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/smart.css" rel="stylesheet" type="text/css">
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/typography.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.filedesc {
  font-size: 12px;
  color: #2E2E2E;
}
.filelink {
  font-size: 14px;
}
.downloads {
  font-size: 14px;
  font-weight: bold;
  color: #0000CC;
  letter-spacing: 3px;
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
            
               <!-- --- show current navigation node branche --- -->
               <div class="branch">Top /
                 <?php  foreach($tpl['nodeBranch'] as $bnode): ?>
                   <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
                 <?php endforeach; ?>
                 <hr class="hr" />
               </div>    
               
               <!-- --- show child nodes of the current navigation node --- -->
               <?php if(count($tpl['childNodes']) > 0): ?>         
               <table width="200"  border="0" align="right" cellpadding="0" cellspacing="0" class="subnodetable">
                 <tr>
                   <td align="left" valign="top" class="subnodetitle">Subnodes</td>
                 </tr>
                 <tr>
                   <td width="190" align="left" valign="top">
                     <ul class="subnodeul">
                       <?php foreach($tpl['childNodes'] as $cnode): ?>
                         <li class="subnodelist">
                           <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $cnode['id_node']; ?>"><?php echo $cnode['title']; ?></a>
                           <?php if(!empty($cnode['short_text'])): ?>
                              <div class="font10"><?php echo $cnode['short_text']; ?></div>
                           <?php endif; ?></li>
                       <?php endforeach; ?>
                    </ul>        
                  </td>
                </tr>       
              </table>    
             <?php endif; ?>
             
           <!-- print title and body of a navigation node -->
           <h3>
             <?php echo $tpl['node']['title'];  ?>
           </h3>
           <div class="text"><?php echo $tpl['node']['body'];  ?></div>
           
           <!-- --- show navigation node related files for download --- -->
           <?php if(count($tpl['nodeFiles'])>0): ?>
             <div class="downloads">Downloads:</div>
             <?php foreach($tpl['nodeFiles'] as $file): ?>
               <table width="350" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="filelink"><a href="<?php echo SMART_RELATIVE_PATH; ?>data/navigation/<?php echo $tpl['node']['media_folder']; ?>/<?php echo $file['file']; ?>"><?php if(!empty($file['title'])){echo $file['title'];}else{echo $file['file'];} ?></a></td>
                </tr>
                <?php if(!empty($file['description'])): ?>
                <tr>
                  <td class="filedesc"><?php echo $file['description']; ?></td>
                </tr>
                <?php endif; ?>
               </table>
             <?php endforeach; ?>
           <?php endif; ?> 
		   
           <!-- --- show navigation node related files for download --- -->
           <?php if(count($tpl['links'])>0): ?>
             <div class="downloads">Links:</div>
             <?php foreach($tpl['links'] as $link): ?>
               <table width="350" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="19" class="filelink"><a href="<?php echo $link['url']; ?>" target="_blank"><?php echo $link['title']; ?></a></td>
                </tr>
                <?php if(!empty($link['description'])): ?>
                <tr>
                  <td class="filedesc"><?php echo $link['description']; ?></td>
                </tr>
                <?php endif; ?>
               </table><br />
             <?php endforeach; ?>
           <?php endif; ?> 		   
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
