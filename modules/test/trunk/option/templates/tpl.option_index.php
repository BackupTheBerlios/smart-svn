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
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#666699">
    <td colspan="2"><span class="style1">&nbsp;&nbsp;&nbsp;Options Management &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="style3">module version: <?php echo $B->sys['module']['option']['version']; ?></span></td>
  </tr>
  <tr>
    <td width="86%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="76%" align="left" valign="top">
    <form action="<?php echo SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1'; ?>&m=option" method="post" name="email" id="email">
        <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td colspan="2" align="left" valign="top"><span class="optiontitle">Administrator email</span></td>
          </tr>
          <tr>
            <td width="87%" align="left" valign="top">
              <input name="site_email" type="text" size="70" maxlength="1024" value="<?php echo htmlspecialchars(commonUtil::stripSlashes_special($B->sys['option']['email'])); ?>">
              &nbsp; 
            </td>
            <td width="13%" align="left" valign="top"><input type="submit" name="update_main_options_email" value="update" onclick="subok(this.form.update_main_options_email);"></td>
          </tr>
        </table>   
    </form> 
    <form action="<?php echo SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1'; ?>&m=option" method="post" name="title" id="title">    
        <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td colspan="2" align="left" valign="top"><span class="optiontitle">Site title and description </span></td>
          </tr>
          <tr>
            <td width="87%" align="left" valign="top">  
                <input name="site_title" type="text" size="70" maxlength="1024" value="<?php echo $B->sys['option']['site_title']; ?>">
                <textarea name="site_desc" cols="50" rows="3" wrap="virtual"><?php echo $B->sys['option']['site_desc']; ?></textarea>
                &nbsp; 
            </td>
            <td width="13%" align="left" valign="top"><input type="submit" name="update_main_options_title" value="update" onclick="subok(this.form.update_main_options_title);"></td>
          </tr>
        </table> 
    </form>  
    <form action="<?php echo SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1'; ?>&m=option" method="post" name="_tpl" id="_tpl">      
        <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td colspan="2" align="left" valign="top"><span class="optiontitle">Public web pages template folders</span></td>
          </tr>
          <tr>
            <td width="13%" align="left" valign="top">
               <select name="tplgroup">
                  <?php foreach($B->templatefolder as $_tpl):  ?>
                     <option value="<?php echo $_tpl; ?>" <?php if($_tpl==$B->sys['option']['tpl']) echo 'selected="selected"' ?>><?php echo $_tpl; ?></option>
                    <?php endforeach;  ?>
               </select>
               &nbsp; 
            </td>
            <td width="87%" align="left" valign="top"><input type="submit" name="update_main_options_tpl" value="update" onclick="subok(this.form.update_main_options_tpl);"></td>
          </tr>
      </table>
    </form>  
    <form action="<?php echo SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1'; ?>&m=option" method="post" name="_view" id="_view">      
        <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td colspan="2" align="left" valign="top"><span class="optiontitle">Public view folders</span></td>
          </tr>
          <tr>
            <td width="13%" align="left" valign="top">
               <select name="viewgroup">
                  <?php foreach($B->viewfolder as $_view):  ?>
                     <option value="<?php echo $_view; ?>" <?php if($_view==$B->sys['option']['view']) echo 'selected="selected"' ?>><?php echo $_view; ?></option>
                    <?php endforeach;  ?>
               </select>
               &nbsp; 
            </td>
            <td width="87%" align="left" valign="top"><input type="submit" name="update_main_options_view" value="update" onclick="subok(this.form.update_main_options_view);"></td>
          </tr>
      </table>
    </form>  
    <form action="<?php echo SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1'; ?>&m=option" method="post" name="_view" id="_view">      
        <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td height="26" colspan="2" align="left" valign="top"><span class="optiontitle">Cache enabled</span></td>
          </tr>
          <tr>
            <td width="13%" align="left" valign="top">
               <input name="cacheenabled" type="checkbox" value="true" <?php if($this->B->sys['option']['cache']==TRUE) echo 'checked="checked"'; ?>>&nbsp; 
               
               
               </td>
            <td width="87%" align="left" valign="top"><input type="submit" name="update_main_options_cache_enabled" value="update" onclick="subok(this.form.update_main_options_cache_enabled);"></td>
          </tr>
      </table>
    </form>      
    <form action="<?php echo SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1'; ?>&m=option" method="post" name="_view" id="_view">      
        <table width="100%"  border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td height="26" colspan="2" align="left" valign="top"><span class="optiontitle">Cache delete</span></td>
          </tr>
          <tr>
            <td width="13%" align="left" valign="top">&nbsp;
               
               </td>
            <td width="87%" align="left" valign="top"><input type="submit" name="update_main_options_cache_delete" value="delete" onclick="subok(this.form.update_main_options_cache_delete);"></td>
          </tr>
      </table>
    </form>                              
        </td>
        <td width="24%" align="right" valign="top"></td>
      </tr>
    </table>        
        <?php 
        // execute option views of other modules
        B( 'get_options' );
        ?>
    </td>
    <td width="24%" align="right" valign="top"></td>
      </tr>
    </table>   