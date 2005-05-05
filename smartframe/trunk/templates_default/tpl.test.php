<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<p><font size="5" face="Verdana, Arial, Helvetica, sans-serif"><strong>Test View is included in Index View</strong></font>
</p>
<p><font color="#990000" size="3" face="Verdana, Arial, Helvetica, sans-serif"><pre><?php print_r( $tpl ); ?></pre></font></p>
</body>
</html>
