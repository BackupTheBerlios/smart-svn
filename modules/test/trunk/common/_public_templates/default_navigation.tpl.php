        <!-- Main Navigation menu included in all templates -->
        
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td align="left" valign="top">
             <font size="2">
              <?php if(!isset($_REQUEST['tpl'])): ?>
                  <strong>Home</strong>
              <?php else: ?>
                  <?php echo "<a href='index.php'>Home</a>"; ?>
              <?php endif; ?>
             </font>
            </td>  
          </tr>
          <?php /* -----------------------------------------------------------    
                   Print out the navigation menu.                            
                   The navigation items array $B->tpl_nav.
                   is produced by the event call at the top of this template. 
                   -----------------------------------------------------------*/ ?>
          <?php foreach($B->tpl_nav as $key => $val): ?>
          <tr>
            <td align="left" valign="top">
              <font size="2">
                <?php if($_REQUEST['tpl'] == $val): ?>
                   <strong><?php echo $key; ?></strong>
                <?php else: ?>
                   <a href="index.php?tpl=<?php echo $val; ?>"><?php echo $key; ?></a>
                <?php endif; ?>
              </font>
            </td>
          </tr>
          <?php endforeach; ?>
          <tr>
            <td align="left" valign="top">&nbsp;</font></td>
          </tr>     
          <tr>
            <td align="left" valign="top"><font size="2"><a href="index.php?admin=1">Admin</a></font></td>
          </tr>     
        </table>