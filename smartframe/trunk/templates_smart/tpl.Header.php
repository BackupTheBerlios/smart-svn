<!-- --- prevent direct all --- -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<style type="text/css">
<!--
.line {
  border-top-width: 0px;
  border-right-width: 0px;
  border-bottom-width: 5px;
  border-left-width: 0px;
  border-top-style: none;
  border-right-style: none;
  border-bottom-style: solid;
  border-left-style: none;
  border-bottom-color: #1958b7;
}
.headerdesc {
  font-size: 1.6em;
  font-weight: bold;
  color: #EAEAEA;
  letter-spacing: 1px;
  word-spacing: 2px;
}
.version {
  font-size: 1.2em;
  color: #CCCCCC;
}
.searchform {
  font-size: 1.2em;
  color: #FFFFFF;
  background-color: #6699CC;
}
.form {
  margin: 0px;
  padding: 0px;
  height: 1.8em;
}
-->
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="50" align="left" valign="top" bgcolor="#2175bc" class="line">
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td width="20%" align="left" valign="top">
          <img src="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/smart_logo.gif" width="129" height="50" border="0"></td>
          <td width="80%" align="left" valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="right" valign="top"></td>
              </tr>
              <tr>
                <td align="left" valign="bottom" class="headerdesc">PHP5 Framework - scalable application design </td>
              </tr>
          </table></td>
        </tr>
    </table></td>
  </tr>
  <tr><td height="18" align="right" valign="middle" bgcolor="#2175bc" class="line">
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="56%" class="version">&nbsp;&nbsp;version: 0.2.5b - 2006-2-20</td>
            <td width="44%" align="right" valign="middle">
                   <form accept-charset="<?php echo $tpl['charset']; ?>" name="form2" method="post" action="<?php echo SMART_CONTROLLER; ?>?view=search" class="form">
                    <input name="search" type="text" value="<?php if(isset($tpl['formsearch'])) echo $tpl['formsearch']; else echo "search"; ?>" size="30" maxlength="255" class="searchform"> &nbsp;
              </form>     
      </td>
          </tr>
         </table>

  </td>
  </tr>
</table>