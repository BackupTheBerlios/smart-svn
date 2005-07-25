<script language="JavaScript" type="text/JavaScript">
</script>
<form name="options" method="post" action="<?php echo SMART_CONTROLLER; ?>?mod=options">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" class="moduleheader2">global options</td>
    </tr>
  <tr>
    <td width="74%" align="left" valign="top">      <table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td align="left" valign="top" class="font10bold">Public template folders</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
		<?php foreach($tpl['allPublicTplFolders'] as $_tpl): ?>
		<input name="templates_folder" type="radio" value="<?php echo $_tpl; ?>"<?php if($tpl['publicTplFolder']==$_tpl) echo " checked"; ?>> 
		<?php echo $_tpl; ?><br /><br />
		<?php endforeach; ?>
		</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Public view folders</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
		<?php foreach($tpl['allPublicViewFolders'] as $_view): ?>
		<input name="views_folder" type="radio" value="<?php echo $_view; ?>"<?php if($tpl['publicViewFolder']==$_view) echo " checked"; ?>> 
		<?php echo $_view; ?><br /><br />
		<?php endforeach; ?>		
		</td>
      </tr>
      <tr>
        <td align="left" valign="top"  class="font10bold">Delete whole public cache</td>
      </tr>
      <tr>
        <td align="left" valign="top"  class="font10bold"><input type="submit" name="deletePublicCache" value="delete public cache"></td>
      </tr>
      <tr>
        <td align="left" valign="top"  class="font10bold">Disable public cache</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10"><input type="checkbox" name="disable_cache" value="1"<?php if($tpl['disableCache']==1) echo " checked "; ?>> 
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="submit" name="updateOptions" value="update"></td>
      </tr>
    </table></td>
    <td width="26%" align="left" valign="top" class="font10bold">&nbsp;</td>
  </tr>
</table>
</form>