<style type="text/css">
<!--
.style1 {
  font-size: 12px;
  font-weight: bold;
  color: #FFFFFF;
}
.style2 {font-size: 14px}
-->
</style>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#666699">
    <td align="left" valign="middle"><span class="style1">&nbsp;&nbsp;&nbsp;User Management</span></td>
    <td align="right" valign="middle"><span class="style1"><a href="modules/user/docs/index.htm" target="_blank">help</a>&nbsp;</span></td>
  </tr>
  <tr>
    <td width="86%" colspan="2" align="left" valign="top">
        <?php /* ### include the module view (template) ### */ ?>
        <?php include( $B->M( MOD_COMMON, 'get_module_view', array('m' => 'user', 'view' => $_REQUEST['sec']) ) ); ?>        
    </td>
  </tr>
</table>
