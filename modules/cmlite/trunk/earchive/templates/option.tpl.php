<table width="100%"  border="0" cellspacing="6" cellpadding="6">
  <tr>
    <td align="left" valign="middle" bgcolor="#CCCCCC" class="optionmodtitle">Earchive Module Options </td>
  </tr>
  <tr>
    <td>
  <form action="index.php?admin=1&m=option" method="post" name="wordindex" id="wordindex">
  <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td colspan="2" align="left" valign="middle" class="optiontitle">Rebuild words index DB table </td>
      </tr>
      <tr>
        <td width="4%" align="left" valign="top"  class="optiondesc"><input name="earchive_rebuild_index" type="checkbox" value="true">
            </td>
        <td width="96%" align="left" valign="top"  class="optiondesc"><input type="submit" name="update_earchive_options_wordindex" value="rebuild" onclick="subok(this.form.update_earchive_options_wordindex);"></td>
      </tr>         
    </table>
  <table width="100%"  border="0" cellspacing="2" cellpadding="2">
    <tr>
      <td colspan="2" align="left" valign="middle" class="optiontitle">Fetch emails</td>
    </tr>
    <tr>
      <td width="4%" align="left" valign="top"  class="optiondesc"><input name="earchive_fetch_emails" type="checkbox" value="true">
      </td>
      <td width="96%" align="left" valign="top"  class="optiondesc"><input type="submit" name="update_earchive_options_fetchemails" value="fetch" onClick="subok(this.form.update_earchive_options_fetchemails);">
</td>
    </tr>
  </table>
  
  <table width="100%"  border="0" cellspacing="2" cellpadding="2">
    <tr>
      <td colspan="2" align="left" valign="middle" class="optiontitle">Also fetch raw headers of emails</td>
    </tr>
    <tr>
      <td width="4%" align="left" valign="top"  class="optiondesc"><input name="earchive_fetch_headers" type="checkbox" value="true" <?php if($B->sys['module']['earchive']['get_header'] == TRUE) echo 'checked="checked"'; ?>>
</td>
      <td width="96%" align="left" valign="top"  class="optiondesc"><input type="submit" name="update_earchive_options_rawheaders" value="update" onclick="subok(this.form.update_earchive_options_rawheaders);">
      </td>
    </tr>
  </table>
  </form>
  </td>
  </tr>
</table>
