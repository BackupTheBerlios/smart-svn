<?php /*
 ### Navigation template. It is loaded by defining the url var view=navigation ### 
     see also /view/class.view_navigation.php
*/ ?>

<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?>

        <!-- Main Navigation menu included in all templates -->
        
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td align="left" valign="top">
             <font size="2">
              <?php if(!isset($_REQUEST['view'])): ?>
                  <strong>Home</strong>
              <?php else: ?>
                  <?php echo "<a href='".SF_CONTROLLER."'>Home</a>"; ?>
              <?php endif; ?>
             </font>
            </td>  
          </tr>
          <?php /* -----------------------------------------------------------    
                   Print out the navigation menu.                            
                   The navigation items array $B->tpl_nav.
                   is produced by the event call at the top of this template. 
                   -----------------------------------------------------------*/ ?>
          <?php foreach($B->tpl_nav as $node): ?>
          <tr>
            <td align="left" valign="top">
              <font size="2">
                <?php if($_REQUEST['node'] == $node['node']): ?>
                   <strong><?php echo $node['title']; ?></strong>
                <?php else: ?>
                   <a href="<?php echo SF_CONTROLLER; ?>?view=node&node=<?php echo $node['node']; ?>"><?php echo $node['title']; ?></a>
                <?php endif; ?>
              </font>
            </td>
          </tr>
          <?php endforeach; ?>
          <tr>
            <td align="left" valign="top">&nbsp;</font></td>
          </tr>     
          <tr>
            <td align="left" valign="top"><font size="2"><a href="<?php echo SF_CONTROLLER; ?>?admin=1">Admin</a></font></td>
          </tr>     
        </table>