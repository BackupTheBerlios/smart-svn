<?php
// ----------------------------------------------------------------------
// Smart (PHP Framework)
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * Module loader of the option module
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// set the base template for this module
$base->tpl->assign( 'module', SF_BASE_DIR . "/admin/modules/option/templates/index.tpl.php" );    

// Assign module handler name
$base->tpl->assign( 'this_module', SF_EVT_HANDLER_OPTION );

?>
