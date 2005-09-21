<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<p>Welcome to the Smart admin management interface.</p>
      <p><strong>NOTICE:</strong> This is a development release. May not every thing works as expected.</p>            <p>Here a couple of short links. Alternate you can switch to the specific modules. See: top right select box</p>
    <p>What would you do?</p>
  <!-- nested includes of whatWouldYouDo views from other modules if provided -->
  <?php $viewLoader->broadcast( 'whatWouldYouDo' ) ?>
  <p>&nbsp;</p>
    <p align="right"><a href="<?php echo SMART_CONTROLLER; ?>?mod=default&view=systemInfo">Show System Info</a>&nbsp;&nbsp;&nbsp;</p>
    <p>&nbsp;</p>
