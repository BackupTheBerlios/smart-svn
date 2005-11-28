<!-- prevent direct all -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<!-- ### charset setting ### -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<meta name="keywords" content="">

<!-- ### output current navigation node title ### -->
<title>SMART3 - <?php echo $tpl['node']['title'];  ?></title>

<!-- ### use allways the php relative path definition to include media files ### -->
<link href="<?php echo $tpl['relativePath']; ?>templates_smart/typography.css" rel="stylesheet" type="text/css">
<link href="<?php echo $tpl['relativePath']; ?>templates_smart/smart.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.desc {
  font-size: 1.2em;
  color: #2E2E2E;
}
.link {
  font-size: 1.2em;
}
.node {
  font-size: 1.2em;
  font-weight: bold;
  color: #0000CC;
}  
-->
</style>
</head>

<body>

<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="maintab">
  <tr>
    <td>
    
        <!-- ### include header view ### -->
        <?php $viewLoader->header();?></td>
  </tr>
  <tr>
    <td><table width="760" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="120" align="left" valign="top" bgcolor="#2096DB" class="leftcol">
            
            <!-- ### include main navigation menu links view ### -->
            <?php $viewLoader->mainNavigation();?>  
    </td>
        <td width="638" align="left" valign="top"><table width="638" border="0" cellspacing="4" cellpadding="2">
          <tr>
            <td align="left" valign="top" width="2%" >
               <table width="100%" border="0" cellspacing="2" cellpadding="2">
                 <tr>
                   <td>
                    <!-- ### show current navigation node branche ### -->
                    <div class="branch">Top /
                     <?php  foreach($tpl['nodeBranch'] as $bnode): ?>
                       <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
                     <?php endforeach; ?><span class="node"><?php echo $tpl['node']['title'];  ?></span>
                     <hr class="hr" />
                    </div>             
           </td>
                 </tr>
                 <tr>
                   <td align="left" valign="top">
                   <table width="100%" border="0" cellpadding="0" cellspacing="0">
                     <tr>
                       <td align="left" valign="top">
             <!-- ### show child nodes of the current navigation node ### -->
                         <?php if(count($tpl['childNodes']) > 0): ?>
                         <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="subnodetable">
                           <tr>
                             <td width="100%" align="left" valign="top">
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
                         </table><hr>
             <?php endif; ?>
                       </td>
                     </tr>
                   </table>
                         <!-- ### show navigation node related files for download ### -->
                         <?php if(count($tpl['links'])>0): ?>
                         <?php foreach($tpl['links'] as $link): ?>
                         <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                             <td height="19" class="link"><a href="<?php echo $link['url']; ?>" target="_blank"><?php echo $link['title']; ?></a></td>
                           </tr>
                           <?php if(!empty($link['description'])): ?>
                           <tr>
                             <td class="desc"><?php echo $link['description']; ?></td>
                           </tr>
                           <?php endif; ?>
                         </table>
                         <br />
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
        
        <!-- ### show Footer text ### -->
        <?php echo $tpl['footer']['body']; ?>        
        </td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
