<style type="text/css">
<!--
.style1 {
    font-size: 12px;
    font-weight: bold;
    color: #FFFFFF;
}
.optiondesc {
    font-size: 10px;
    color: #333333;
    font-weight: bold;
}
.optiontitle {
    font-size: 12px;
    color: #3333CC;
    font-weight: bold;
}
.optionmodtitle {
    font-size: 14px;
    color: #3333FF;
    font-weight: bold;
}
-->
</style>
<form action="index.php?m=OPTION" method="post" name="url" id="url">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#666699">
    <td colspan="2"><span class="style1">&nbsp;&nbsp;&nbsp;Options Management</span></td>
  </tr>
  <tr>
    <td width="86%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="76%" align="left" valign="top">
                <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td align="left" valign="top"><span class="optiontitle">URL of the public page </span></td>
          </tr>
          <tr>
            <td align="left" valign="top">                      
              <input name="site_url" type="text" size="70" maxlength="1024" value="<?php echo htmlspecialchars($B->util->stripSlashes($B->sys['option']['url'])); ?>">
&nbsp; 
           </td>
          </tr>
        </table>
                <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td align="left" valign="top"><span class="optiontitle">Administrator email</span></td>
          </tr>
          <tr>
            <td align="left" valign="top">
              <input name="site_email" type="text" size="70" maxlength="1024" value="<?php echo htmlspecialchars($B->util->stripSlashes($B->sys['option']['email'])); ?>">
&nbsp; 
            </td>
          </tr>
        </table>        
                <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td align="left" valign="top"><span class="optiontitle">Site title and description </span></td>
          </tr>
          <tr>
            <td align="left" valign="top">  
                                      <input name="site_title" type="text" size="70" maxlength="1024" value="<?php echo htmlspecialchars($B->util->stripSlashes($B->sys['option']['site_title'])); ?>">
<textarea name="site_desc" cols="50" rows="3" wrap="virtual"><?php echo htmlspecialchars($B->util->stripSlashes($B->sys['option']['site_desc'])); ?></textarea>
&nbsp; 
            </td>
          </tr>
        </table>                
                <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td align="left" valign="top"><span class="optiontitle">Charset</span></td>
          </tr>
          <tr>
            <td align="left" valign="top">
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
            </td>
          </tr>
        </table>        
                <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td align="left" valign="top"><span class="optiontitle">Public web pages templates</span></td>
          </tr>
          <tr>
            <td align="left" valign="top">
               <select name="tpl">
                             <?php foreach($B->templ as $_tpl):  ?>
                             <option value="<?php echo $_tpl; ?>" <?php if($_tpl==$B->sys['option']['tpl']) echo 'selected="selected"' ?>><?php echo $_tpl; ?></option>
                             <?php endforeach;  ?>
                             </select>
&nbsp; 
            </td>
          </tr>
          <tr>
              <td align="left" valign="top"><span class="optiontitle">Bad words language lists</span></td>
          </tr>
          <tr>
              <td align="left" valign="top">
                <select name="bad_word_list">
                <option value="">Select language</option>
                <?php foreach($B->tpl_bad_word_lang as $lang): ?>
                    <option value="<?php echo $lang ?>"><?php echo $lang ?></option>
                <?php endforeach; ?>
                </select>
              </td>
          </tr>
            <?php if(count($B->tpl_selected_lang) != 0): ?>
          <tr>
              <td align="left" valign="top">              <table width="250" border="0" cellspacing="2" cellpadding="2">
                  <tr align="left" valign="top">
                      <td colspan="2" class="optiondesc">&nbsp;Selected languages</td>
                  </tr>
                <?php foreach($B->tpl_selected_lang as $sel_lang): ?>
                  <tr>
                      <td width="14" align="left" valign="middle" class="optiondesc">&nbsp;</td>
                      <td width="222" align="left" valign="top" class="optiondesc"><input name="selected_lang[]" type="checkbox" value="<?php echo $sel_lang; ?>"> <?php echo $sel_lang; ?></td>
                  </tr>
                  <?php endforeach; ?>
              </table></td>
          </tr>
          <?php endif; ?>
        </table>
        <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td align="left" valign="top"><span class="optiontitle">Clean public web cache</span></td>
          </tr>
          <tr>
            <td align="left" valign="top">
                             &nbsp; 
             <input name="clean_cache" type="submit" id="clean_cache" value="clean cache"></td>
          </tr>
        </table>                                
                </td>
        <td width="24%" align="right" valign="top"></td>
      </tr>
    </table>        
        <?php 
        // include otions templates of other modules
        if(count($B->mod_option) > 0)
        {
            foreach($B->mod_option as $option)
            {
                include $option;
            }
        }
        ?>
        <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top">
                             &nbsp; 
<input name="update_main_options" type="submit" id="update_main_otions" value="Update">            </td>
          </tr>
        </table>                                
                </td>
        <td width="24%" align="right" valign="top"></td>
      </tr>
    </table>   
 </form>