<!-- prevent direct all -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<!-- --- charset setting --- -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<meta name="keywords" content="">

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
                      
                      <?php if(isset($tpl['showComments'])): ?>
                      <a name="comments"></a>
                      <div class="downloads"><h2>Article comments: </h2></div>
                         <?php foreach($tpl['articleComments'] as $comment): ?>
                         <table width="500" border="0" cellspacing="2" cellpadding="2">
                           <tr>
                             <td width="10" align="left" valign="top" class="filelink">-</td>
                             <td width="490" align="left" valign="top" class="filelink">
                                Posted by <?php echo $comment['author']; ?>
                                at <?php echo $comment['pubdate']; ?>
                                <?php if(!empty($comment['url'])): ?>
                                  / <a href="<?php echo $comment['url']; ?>">url</a>
                                <?php  endif; ?>
                             </td>
                           </tr>
                           <tr>
                             <td align="left" valign="top" class="filedesc">&nbsp;</td>
                             <td align="left" valign="top" class="filedesc"><?php echo $comment['body']; ?></td>
                           </tr>
                         </table>
                         <hr />
                         <?php endforeach; ?>
                      <?php endif; ?>  
                      
                      <?php if(isset($tpl['showCommentForm'])): ?>
                      <a name="commentform"></a>
                      <div class="downloads"><h3>Add article comment: </h3></div>
                      <?php if(!empty($tpl['cmessage'])): ?>
                          <div style="font-size:14; color:#FF0000; font-weight: bold;"><?php echo $tpl['cmessage']; ?></div>
                      <?php endif; ?>                      
                        <form name="comment" accept-charset="<?php echo $tpl['charset']; ?>" method="post" action="<?php echo SMART_CONTROLLER; ?>?id_article=<?php echo $tpl['article']['id_article']; ?>#commentform">
                         <table width="500" border="0" cellspacing="2" cellpadding="2">
                           <tr>
                             <td width="120" align="left" valign="top" class="filelink"><strong>Author: </strong></td>
                             <td width="380" align="left" valign="top" class="filelink">
                               <input name="cauthor" type="text" value="<?php echo $tpl['cauthor']; ?>" size="50" maxlength="255">
                             </td>
                           </tr>
                           <tr>
                             <td align="left" valign="top" class="filelink"><strong>Email: </strong></td>
                             <td align="left" valign="top" class="filelink">
                               <input name="cemail" type="text" value="<?php echo $tpl['cemail']; ?>" size="50" maxlength="255">
                             </td>
                           </tr>
                           <tr>
                             <td align="left" valign="top" class="filelink"><strong>Url: </strong></td>
                             <td align="left" valign="top" class="filelink">
                               <input name="curl" type="text" value="<?php echo $tpl['curl']; ?>" size="50" maxlength="255">
                             </td>
                           </tr>
                           <tr>
                             <td align="left" valign="top" class="filelink"><strong>Comment: </strong></td>
                             <td align="left" valign="top" class="filelink">
                               <textarea name="cbody" cols="50" rows="15" id="cbody"><?php echo $tpl['cbody']; ?></textarea>
                             </td>
                           </tr>
                           <tr>
                             <td align="left" valign="top" class="filelink"><strong>Turing Key: </strong></td>
                             <td valign="top" align="left" >
                               <div><input type="text" name="captcha_turing_key" value="" maxlength="5" size="10">
                               <input type="hidden" name="captcha_public_key" value="<?php echo $tpl['public_key']; ?>" maxlength="5" size="40">
                               </div>
                               <img src="<?php echo $tpl['captcha_pic']; ?>" border="1">
                             </td>
                           </tr>
                           <tr>
                           
                             <td align="left" valign="top" class="filelink" colspan="2">
                               <input name="addComment" type="submit" id="addComment" value="add comment">
         
                             </td>
                           </tr>
                         </table>

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
