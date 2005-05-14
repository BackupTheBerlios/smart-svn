<?php /*
 ### Error template. It is loaded when a view class produce an error ### 
*/ ?>

<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="moduleheader">Main Page</td>
  </tr>
  <tr>
    <td><p>Welcome to the Smart admin management interface.</p>
      <p><strong>NOTICE:</strong> This is a development release. Not every thing works as expected.</p>            <p>Here a couple of short links. Alternate you can switch to the specific modules. See: top right select box</p>
    <p>What would you do?</p>
	<?php $viewLoader->broadcast( 'whatWouldYouDo' ) ?>    <p>&nbsp;</p>
    <p>&nbsp;</p></td>
  </tr>
</table>
