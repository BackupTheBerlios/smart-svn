            <table width="100%"  border="0" cellspacing="4" cellpadding="2">
              <tr>
                <td align="left" valign="top" class="leftnavlinks">
                  <?php if(!isset($_GET['view'])): ?>
                  <strong>Home</strong>
                  <?php else: ?>
                  <a href="<?php echo SF_CONTROLLER; ?>?mode=<?php echo $_REQUEST['mode']; ?>">Home</a>
                  <?php endif; ?>
                </td>
              </tr>
              <tr>
                <td align="left" valign="top" class="leftnavlinks"><a href="<?php echo SF_CONTROLLER; ?>?admin=1">
                Admin</a></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="leftnavlinks pager">&nbsp;</td>
              </tr>
            </table>
            <table width="100%"  border="0" cellspacing="6" cellpadding="0">
              <tr>
                <td width="1%" colspan="2" align="left" valign="top"><span class="style3">E-archives</span></td>
              </tr>
              <?php //show available lists links ?>
              <?php if (count($B->tpl_list) > 0): ?>
              <?php foreach($B->tpl_list as $list): ?>
              <tr>
                <td width="1%" align="left" valign="top" class="leftnavlinks">-</td>
                <td width="99%" align="left" valign="top" class="leftnavarchives">
                  <?php if($list['lid']==$_GET['lid']): ?>
                    <strong><?php echo $list['name'];  ?></strong>
                  <?php else: ?>
                    <a href="<?php echo SF_CONTROLLER; ?>?view=list&amp;lid=<?php echo $list['lid']; ?>&amp;mode=<?php echo $_REQUEST['mode']; ?>"><?php echo $list['name']; ?></a>
                  <?php endif;  ?>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php else: ?>
              <tr>
                <td width="1%" align="left" valign="top" class="leftnavlinks">-</td>
                <td width="99%" align="left" valign="top" class="leftnavarchives">no archive</td>
              </tr>
              <?php endif; ?>
            </table>
