<?php if(!isset($_REQUEST['nodecoration'])): ?>
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
    <td align="left" valign="middle"><span class="style1">&nbsp;&nbsp;&nbsp;Site Navigation Nodes Management &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="style3">module version: <?php echo $B->sys['module']['navigation']['version']; ?></span></td>
    <td align="right" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td width="86%" colspan="2" align="left" valign="top">
        <?php endif; ?>
        <?php /* ### include the module view (template) ### */ ?>
        <?php M( MOD_SYSTEM, 'get_view', array('m' => 'navigation', 'view' => $_REQUEST['sec']) ); ?>        
    <?php if(!isset($_REQUEST['nodecoration'])): ?>
    </td>
  </tr>
</table>
<?php endif; ?>