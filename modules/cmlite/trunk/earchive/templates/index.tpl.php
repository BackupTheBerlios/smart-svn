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
    <td><span class="style1">&nbsp;&nbsp;&nbsp;Email Archive Management</span></td>
  </tr>
  <tr>
    <td width="86%" align="left" valign="top">
        <?php /* ### include the module view (template) ### */ ?>
        <?php include( $B->M( MOD_COMMON, 'get_module_view', array('m' => 'earchive', 'tpl' => $_REQUEST['sec']) ) ); ?>            
    </td>
  </tr>
</table>
