<table width="100%"  border="0" cellspacing="6" cellpadding="6">
  <tr>
    <td align="left" valign="middle" bgcolor="#CCCCCC" class="optionmodtitle">Earchive Module Options </td>
  </tr>
  <tr>
    <td>
	<form action="index.php?m=OPTION" method="post" name="wordindex" id="wordindex">
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
	</form>
	<form action="index.php?m=OPTION" method="post" name="fetchemails" id="fetchemails">
        <table width="100%"  border="0" cellspacing="2" cellpadding="2">
            <tr>
                <td colspan="2" align="left" valign="middle" class="optiontitle">Fetch emails</td>
            </tr>
            <tr>
                <td width="4%" align="left" valign="top"  class="optiondesc"><input name="earchive_fetch_emails" type="checkbox" value="true">
                </td>
                <td width="96%" align="left" valign="top"  class="optiondesc"><input type="submit" name="update_earchive_options_fetchemails" value="fetch" onclick="subok(this.form.update_earchive_options_fetchemails);"></td>
            </tr>
        </table>
		</form>
		</td>
  </tr>
</table>
