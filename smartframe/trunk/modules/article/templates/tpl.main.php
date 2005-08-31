<script language="JavaScript" type="text/JavaScript">
function nodemap(){
mm='scrollbars=1,toolbar=0,menubar=0,resizable=no,width=400,height=450';
newwindow= window.open('<?php echo SMART_CONTROLLER; ?>?nodecoration=1&mod=navigation&view=nodemap&openerModule=article','',mm); }
</script>
<style type="text/css">
<!--
.optsel {
  background-color: #CCCCCC;
}
-->
</style>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
  <div class="font12 indent5">
  <a href="<?php echo SMART_CONTROLLER; ?>?mod=article">Top</a>
  <?php foreach($tpl['branch'] as $node): ?>
   / <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&id_node=<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a>
  <?php endforeach; ?>
  <?php if($tpl['id_node']!=0): ?>
     <span class="font12bold"> / <?php echo $tpl['node']['title']; ?></span>
  <?php endif; ?>
  </div>
  <?php if(isset($tpl['nodes']) && (count($tpl['nodes'])>0)): ?>
    <?php foreach($tpl['nodes'] as $node): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="1%" align="left" valign="top" class="font10">&nbsp;</td>
        <td width="1%" align="left" valign="top" class="font10">
           <?php if($node['status']==1): ?>
              <img src="./modules/common/media/pics/inactive.png" width="21" height="21">
           <?php elseif($node['status']==2): ?>
              <img src="./modules/common/media/pics/active.png" width="21" height="21">
           <?php endif; ?>		
		</td>
        <td width="98%" align="left" valign="top" class="font10">
          <?php echo '<a href="'.SMART_CONTROLLER.'?mod=article&id_node='.$node['id_node'].'">'.$node['title'].'</a>'; ?>
        </td>
      </tr>
    </table>
    <?php endforeach; ?>
   <?php endif; ?>
  <?php if(isset($tpl['articles']) && (count($tpl['articles'])>0)): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCFF" class="font12bold">&nbsp;Articles</td>
      </tr>  
	  </table>
    <?php if(count($tpl['articles'])>0): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
	<?php endif; ?>
    <?php foreach($tpl['articles'] as $article): ?>	
      <tr>
        <td width="1" align="left" valign="top" class="font10">
    <?php if($article['status']==0): ?>
      delete
    <?php elseif($article['status']==1): ?>
      cancel
    <?php elseif($article['status']==2): ?>
      propose
    <?php elseif($article['status']==3): ?>
      edit
    <?php elseif($article['status']==4): ?>
      publish
    <?php elseif($article['status']==5): ?>
      restrict	  	  	  	  
    <?php endif; ?>
    </td>
        <td width="1" align="left" valign="top" class="itemnormal"><?php if($tpl['showArticle']==TRUE): ?><a href="<?php echo SMART_CONTROLLER; ?>?mod=article&id_article_up=<?php echo $article['id_article']; ?>&id_node=<?php echo $tpl['id_node']; ?>"><img src="./modules/common/media/pics/up.png" width="21" height="21" border="0"></a><?php else: ?>&nbsp;<?php endif; ?></td>
        <td width="1" align="left" valign="top" class="itemnormal"><?php if($tpl['showArticle']==TRUE): ?><a href="<?php echo SMART_CONTROLLER; ?>?mod=article&id_article_down=<?php echo $article['id_article']; ?>&id_node=<?php echo $tpl['id_node']; ?>"><img src="./modules/common/media/pics/down.png" width="21" height="21" border="0"></a><?php else: ?>&nbsp;<?php endif; ?></td>
        <td width="99%" align="left" valign="top" class="itemnormal">
		  <?php if(($tpl['showArticle']==TRUE)&&($article['lock']==FALSE)): ?><a href="<?php echo SMART_CONTROLLER; ?>?mod=article&view=editArticle&id_node=<?php echo $tpl['id_node']; ?>&id_article=<?php echo $article['id_article']; ?>"><?php echo $article['title']; ?></a><?php else: ?><?php echo $article['title']; ?><?php endif; ?>
				<?php if(!empty($article['description'])): ?>
				   <div class="font10"><?php echo $article['description']; ?></div>
				<?php endif; ?>
        </td>
      </tr>
	<?php endforeach; ?>  
	<?php if(count($tpl['articles'])>0): ?>
    </table>
    <?php endif; ?> 
	
	<?php elseif(($tpl['showArticle']==TRUE)&&($tpl['showAddArticle']==TRUE)): ?>
        <br><br><div class="font12bold">There is currently no article under this node. You may add some one here.</div><br><br>  
    <?php endif; ?>   
    </td>
    <td width="11%" align="center" valign="top" class="font12">
    <?php if(($tpl['showArticle']==TRUE)&&($tpl['showAddArticle']==TRUE)): ?>
       <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&view=addArticle&id_node=<?php echo $tpl['id_node']; ?>">add article</a>
    <?php endif; ?>
    <p><a href="javascript:nodemap();">NodesMap</a></p></td>
  </tr>
</table>
