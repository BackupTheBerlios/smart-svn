<!-- prevent direct all -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<!-- --- charset setting --- -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<meta name="keywords" content="">

<!-- --- output current navigation node title --- -->
<title>SMART3 PHP5 Framework- Blog</title>
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
  padding-left: 20px;
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
<style type="text/css">
<!--
.barticletitle {
	font-size: 14px;
	font-weight: bold;
}
.barticlebody {
	font-size: 10px;
}
.barticlefooter {
	font-size: 10px;
	margin-top: 5px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 0px;
	border-top-width: 1px;
	border-right-width: 0px;
	border-bottom-width: 0px;
	border-left-width: 0px;
	border-top-style: solid;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	border-top-color: #00CCFF;
}
-->
</style>
<style type="text/css">
<!--
.barticletable {
	border: 1px solid #00CCCC;
	margin: 5px;
	padding-top: 3px;
	padding-right: 3px;
	padding-bottom: 3px;
	padding-left: 3px;
}
-->
</style>
<style type="text/css">
<!--
.cattitle {
	font-size: 12px;
	font-weight: bold;
	color: #0066CC;
	background-color: #EAEAEA;
	padding-top: 2px;
	padding-right: 2px;
	padding-bottom: 2px;
	padding-left: 2px;
}
.catnode {
	font-size: 10px;
	font-weight: bold;
	padding-top: 2px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 4px;
}
-->
</style>
<style type="text/css">
<!--
.cattable {
	margin: 2px;
	padding: 2px;
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
                    <div class="branch">
                     <?php  foreach($tpl['nodeBranch'] as $bnode): ?>
                       <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
                     <?php endforeach; ?> <strong> <?php echo $tpl['node']['title'];  ?> </strong>
                     <hr class="hr" />       
           </td>
                 </tr>
                 <tr>
                   <td align="left" valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="81%" align="left" valign="top">
	<?php foreach($tpl['allArticles'] as $article): ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="3" class="barticletable">
      <tr>
        <td height="28" align="left" valign="top" class="barticletitle">
		<a href="<?php echo SMART_CONTROLLER; ?>?id_article=<?php echo $article['id_article']; ?>"><?php echo $article['title']; ?></a>
		</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="barticlebody">
		<?php echo $article['body']; ?>
		</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="barticlefooter">
		Category: <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $article['id_node']; ?>"><?php echo $article['node']['title']; ?></a><br>
		Date: <?php echo $article['pubdate']; ?> / 
		<?php if($article['num_comments'] > 0): ?>
		    <a href="<?php echo SMART_CONTROLLER; ?>?id_article=<?php echo $article['id_article']; ?>#comments">comments: <?php echo $article['num_comments']; ?></a>
		<?php else: ?>
		    comments: <?php echo $article['num_comments']; ?>
		<?php endif; ?>
		</td>
      </tr>
    </table>
	<?php endforeach; ?>
    <?php if(!empty($tpl['pager'])): ?>
	 <table width="100%" border="0" cellspacing="0" cellpadding="3" class="barticletable">
        <tr>
            <td width="100%" align="left" valign="middle">
              <?php echo $tpl['pager']; ?>
            </td>
        </tr>
     </table>				   
    <?php endif; ?>
	</td>
    <td width="19%" align="center" valign="top">
	<?php if(count($tpl['childNodes'])>0): ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="2" class="cattable">
      <tr>
        <td align="left" valign="middle" class="cattitle">Categories</td>
      </tr>
	  <?php foreach($tpl['childNodes'] as $category): ?>
      <tr>
        <td align="left" valign="top" class="catnode">
		 <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $category['id_node']; ?>"><?php echo $category['title']; ?></a>
		</td>
      </tr>
	  <?php endforeach; ?>
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
