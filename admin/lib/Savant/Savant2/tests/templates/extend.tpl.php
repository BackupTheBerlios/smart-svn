<?php
/**
* 
* Template for testing token assignment.
* 
* @version $Id: extend.tpl.php,v 1.1 2004/06/21 14:01:57 pmjones Exp $
*
*/
?>
<p><?php $_result = $this->plugin('example'); var_dump($_result); ?></p>
<p><?php $_result = $this->plugin('example_extend'); var_dump($_result); ?></p>
