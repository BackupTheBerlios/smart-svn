<style type="text/css">
<!--
.style1 {
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
}
.style3 {
	font-size: 12px;
	color: #3333CC;
	font-weight: bold;
}
-->
</style>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#666699">
    <td><span class="style1">&nbsp;&nbsp;&nbsp;Options Management</span></td>
  </tr>
  <tr>
    <td width="86%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="76%" align="left" valign="top">
				<table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td align="left" valign="top"><span class="style3">URL of the public page </span></td>
          </tr>
          <tr>
            <td align="left" valign="top">						<form action="index.php?m=option" method="post" name="url" id="url">
              <input name="textfield" type="text" size="70" maxlength="1024" value="<?php echo htmlspecialchars($B->util->stripSlashes($B->sys['option']['url'])); ?>">
&nbsp; 
<input name="update_url" type="submit" id="update_url" value="Update">           
            </form></td>
          </tr>
        </table>
				<table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td align="left" valign="top"><span class="style3">Administrator email</span></td>
          </tr>
          <tr>
            <td align="left" valign="top">
						<form action="index.php?m=option" method="post" name="url" id="url">
              <input name="textfield" type="text" size="70" maxlength="1024" value="<?php echo htmlspecialchars($B->util->stripSlashes($B->sys['option']['adminemail'])); ?>">
&nbsp; 
<input name="update_url" type="submit" id="update_email" value="Update">           
            </form></td>
          </tr>
        </table>		
				<table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td align="left" valign="top"><span class="style3">Site title and description </span></td>
          </tr>
          <tr>
            <td align="left" valign="top">						<form action="index.php?m=option" method="post" name="url" id="url">
              <input name="textfield" type="text" size="70" maxlength="1024" value="<?php echo htmlspecialchars($B->util->stripSlashes($B->sys['option']['site_title'])); ?>">
<textarea name="site_desc" cols="50" rows="3" wrap="virtual"><?php echo htmlspecialchars($B->util->stripSlashes($B->sys['option']['site_desc'])); ?></textarea>
&nbsp; 
<input name="update_url" type="submit" id="update_site" value="Update">           
            </form></td>
          </tr>
        </table>				
				<table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td align="left" valign="top"><span class="style3">Charset</span></td>
          </tr>
          <tr>
            <td align="left" valign="top">
						<form action="index.php?m=option" method="post" name="url" id="url">
<select name="charset">
          <option value="iso-8859-1" <?php if($B->sys['option']['charset']=='iso-8859-1') echo 'selected="selected"' ?>>Western (iso-8859-1)</option>
          <option value="iso-8859-15" <?php if($B->sys['option']['charset']=='iso-8859-15') echo 'selected="selected"' ?>>Western (iso-8859-15)</option>
          <option value="iso-8859-2" <?php if($B->sys['option']['charset']=='iso-8859-2') echo 'selected="selected"' ?>>Central European (iso-8859-2)</option>
          <option value="iso-8859-3" <?php if($B->sys['option']['charset']=='iso-8859-3') echo 'selected="selected"' ?>>South European (iso-8859-3)</option>
          <option value="iso-8859-4" <?php if($B->sys['option']['charset']=='iso-8859-4') echo 'selected="selected"' ?>>Baltic (iso-8859-4)</option>
          <option value="iso-8859-13" <?php if($B->sys['option']['charset']=='iso-8859-13') echo 'selected="selected"' ?>>Baltic (iso-8859-13)</option>
          <option value="iso-8859-5" <?php if($B->sys['option']['charset']=='iso-8859-5') echo 'selected="selected"' ?>>Cyrillic (iso-8859-5)</option>
          <option value="iso-8859-6" <?php if($B->sys['option']['charset']=='iso-8859-6') echo 'selected="selected"' ?>>Arabic (iso-8859-6)</option>
          <option value="iso-8859-7" <?php if($B->sys['option']['charset']=='iso-8859-7') echo 'selected="selected"' ?>>Greek (iso-8859-7)</option>
          <option value="iso-8859-8" <?php if($B->sys['option']['charset']=='iso-8859-8') echo 'selected="selected"' ?>>Hebrew (iso-8859-8)</option>
          <option value="iso-8859-9" <?php if($B->sys['option']['charset']=='iso-8859-9') echo 'selected="selected"' ?>>Turkish (iso-8859-9)</option>
          <option value="iso-8859-10" <?php if($B->sys['option']['charset']=='iso-8859-10') echo 'selected="selected"' ?>>Nordic (iso-8859-10)</option>
          <option value="iso-8859-11" <?php if($B->sys['option']['charset']=='iso-8859-11') echo 'selected="selected"' ?>>Thai (iso-8859-11)</option>
          <option value="iso-8859-14" <?php if($B->sys['option']['charset']=='iso-8859-14') echo 'selected="selected"' ?>>Celtic (iso-8859-14)</option>
          <option value="iso-8859-16" <?php if($B->sys['option']['charset']=='iso-8859-16') echo 'selected="selected"' ?>>Romanian (iso-8859-16)</option>
          <option value="utf-8" <?php if($B->sys['option']['charset']=='utf-8') echo 'selected="selected"' ?>>Unicode (utf-8)</option>
        </select>&nbsp; 
<input name="update_url" type="submit" id="update_charset" value="Update">           
            </form></td>
          </tr>
        </table>		
				<table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td align="left" valign="top"><span class="style3">Public web pages templates</span></td>
          </tr>
          <tr>
            <td align="left" valign="top">						<form action="index.php?m=option" method="post" name="url" id="url">
               <select name="tpl">
							 <?php foreach($B->templ as $_tpl):  ?>
							 <option value="<?php echo $_tpl; ?>" <?php if($_tpl==$B->sys['option']['tpl']) echo 'selected="selected"' ?>><?php echo $_tpl; ?></option>
							 <?php endforeach;  ?>
							 </select>
							 &nbsp; 
<input name="update_url" type="submit" id="update_site" value="Update">           
            </form></td>
          </tr>
        </table>										
				</td>
        <td width="24%" align="right" valign="top"></td>
      </tr>
    </table>      </td>
  </tr>
</table>
