<style type="text/css">
<!--
.style3 {   font-size: 12px;
    color: #3333CC;
    font-weight: bold;
}
.style1 {   font-size: 12px;
    font-weight: bold;
    color: #FFFFFF;
}
-->
</style>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#666699">
    <td><span class="style1">&nbsp;&nbsp;&nbsp;<?php echo $B->tpl_test_title; ?></span></td>
  </tr>
  <tr>
    <td width="86%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="76%" align="left" valign="top">
      <table width="100%"  border="0" cellspacing="6" cellpadding="6">
            <tr>
              <td align="left" valign="top">
        <p><?php echo $B->tpl_test_intro_text; ?></p>
            <p><?php foreach($B->tpl_test_counter as $counter) echo $counter.' '; ?></p>
          <p>Test Form</p>
          <!-- 'm' stay for the module name. 'mf' stay for the modul feature -->
          <!--  so this form send data to the TEST modul and activate the module feature 'evalform' . see admin event handler of the test modul -->
          <form name="form1" method="post" action="index.php?admin=1&m=test&tpl=index&action=evalform">
            <input name="testfield" type="text" id="testfield" size="40" maxlength="255">
                      <input type="submit" name="Submit" value="Submit">
                    </form>
          <?php if(!empty($B->tpl_test_form_text)): ?>
          <p><strong>you entered the following text in form:</strong><br />
          <!-- the modul TEST evaluate the form data entered before and assign the form text to the following template var -->
          <?php echo $B->tpl_test_form_text; ?>
          </p>
          <?php endif; ?>
       </td>
            </tr>
          </table></td>
        </tr>
    </table></td>
  </tr>
</table>
