<style type="text/css">
<!--
.style1 {
  font-size: 12px;
  font-weight: bold;
  color: #FFFFFF;
}
.style3 {
  font-size: 12px;
  color: #FFFFFF;
}
.style2 {font-size: 14px}
-->
</style>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#666699">
    <td><span class="style1">&nbsp;&nbsp;&nbsp;Email Archive Management &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="style3">module version: <?php echo $B->sys['module']['earchive']['version']; ?></span></td>
  </tr>
  <tr>
    <td width="86%" align="left" valign="top">
        <?php /* ### include the module view (template) ### */ ?>
        <?php 
            if(empty($_REQUEST['sec']))
            {
                $_REQUEST['sec'] = 'default';  
            }
            M( MOD_SYSTEM, 'get_view', array('m' => 'earchive', 'view' => $_REQUEST['sec']) ); 
        ?>            
    </td>
  </tr>
</table>
