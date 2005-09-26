<div id="navcontainer">
<ul id="navlist">

  <!-- link to the entry page -->
  <li><a href="{$tpl.smartController}{if $tpl.lang>''}?lang={$tpl.lang}{/if}">Home</a></li>
  
  <!-- output all root navigation nodes -->
  {foreach item=node from=$tpl.rootNodes}   
  <li><a href="{$tpl.smartController}?id_node={$node.id_node}">{$node.title}</a></li>
  {/foreach}

  <!-- show logout link if user is logged -->
  {if $tpl.isUserLogged==TRUE}
  <li>&nbsp;</li>
  <li><a href="{$tpl.smartController}?view=logout">Logout</a></li>
  {/if}  
   
</ul>
</div>