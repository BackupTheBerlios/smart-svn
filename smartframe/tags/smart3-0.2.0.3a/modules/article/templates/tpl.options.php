<script language="JavaScript" type="text/JavaScript">
</script>
<form name="options" method="post" action="<?php echo SMART_CONTROLLER; ?>?mod=article&view=options">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" class="moduleheader2">Article module options</td>
    </tr>
  <tr>
    <td width="74%" align="left" valign="top">      <table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td align="left" valign="top" class="font10bold">Thumbnail width in pixels</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="thumb_width" type="text" value="<?php echo $tpl['option']['thumb_width']; ?>" size="4" maxlength="4"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Max. image file size in bytes</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="img_size_max" type="text" size="6" maxlength="6" value="<?php echo $tpl['option']['img_size_max']; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Max. attached file size in bytes</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="file_size_max" type="text" size="6" maxlength="6" value="<?php echo $tpl['option']['file_size_max']; ?>"></td>
      </tr>
	  <!-- For a later release
      <tr>
        <td align="left" valign="top"  class="font10bold">Fromat of the node body textareas</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
          <input type="radio" name="force_format" value="2" <?php if($tpl['option']['force_format']==2) echo "checked"; ?>>
    Tiny Mice &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="force_format" value="1" <?php if($tpl['option']['force_format']==1) echo "checked"; ?>>
    Text Wikki &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="force_format" value="0" <?php if($tpl['option']['force_format']==0) echo "checked"; ?>>
    Custom </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Fromat if custom</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="default_format" value="2" <?php if($tpl['option']['default_format']==2) echo " checked"; ?>>
    Tiny Mice &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="default_format" value="1"<?php if($tpl['option']['default_format']==1) echo " checked"; ?> >
    Text Wikki &nbsp;&nbsp;</td>
      </tr>
	  -->
      <tr>
        <td align="left" valign="top" class="font10bold">Use article related content</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
		<input type="checkbox" name="use_logo" value="1"<?php if($tpl['option']['use_logo']==1) echo " checked "; ?>> Logo<br>
		<input type="checkbox" name="use_images" value="1"<?php if($tpl['option']['use_images']==1) echo " checked "; ?>> Images<br>
		<input type="checkbox" name="use_files" value="1"<?php if($tpl['option']['use_files']==1) echo " checked "; ?>> Files<br>
		<input type="checkbox" name="use_overtitle" value="1"<?php if($tpl['option']['use_overtitle']==1) echo " checked "; ?>> Overtitle<br>
		<input type="checkbox" name="use_subtitle" value="1"<?php if($tpl['option']['use_subtitle']==1) echo " checked "; ?>> Subtitle<br>
		<input type="checkbox" name="use_description" value="1"<?php if($tpl['option']['use_description']==1) echo " checked "; ?>> Description<br>
		<input type="checkbox" name="use_header" value="1"<?php if($tpl['option']['use_header']==1) echo " checked "; ?>> Header<br>
		<input type="checkbox" name="use_ps" value="1"<?php if($tpl['option']['use_ps']==1) echo " checked "; ?>> PS<br>
		<input type="checkbox" name="use_changedate" value="1"<?php if($tpl['option']['use_changedate']==1) echo " checked "; ?>> Change Date<br>
		<input type="checkbox" name="use_articledate" value="1"<?php if($tpl['option']['use_articledate']==1) echo " checked "; ?>> Article Date<br>
		</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="submit" name="updateOptions" value="update"></td>
      </tr>
    </table></td>
    <td width="26%" align="left" valign="top" class="font10bold"><a href="<?php echo SMART_CONTROLLER; ?>?mod=article">back to article module</a>
	  <?php if(count($tpl['error'])>0):  ?><br>
	  <br>
	    <?php foreach($tpl['error'] as $error): ?>
		   <?php echo $error; ?><br><br>
		<?php endforeach; ?>
	  <?php endif; ?>
	</td>
  </tr>
</table>
</form>