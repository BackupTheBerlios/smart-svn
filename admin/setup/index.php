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
 * The main setup file
 *
 */
 
/*
 * Secure include of files from this script
 */
define('SF_SECURE_INCLUDE', 1);

// Define the absolute path to the Smart base
//
define('SF_BASE_DIR', dirname(dirname(dirname(__FILE__))));

// Define section area
define ('SF_SECTION', 'admin');

// Include the base file
include( SF_BASE_DIR."/admin/setup/setup.inc.php" );

// set the base template for this module
$base->tpl->readTemplatesFromInput(  "/admin/setup/index.tpl.html" ); 

// launch setup
if( $_POST['do_setup'] )
{
    // Send a setup message to all registered handlers
    $base->event->broadcast_run( SF_EVT_SETUP );
    
    // if there are no errors go to the admin section
    if( isset($base->tmp_error_system) && (count($base->tmp_error_system) == 0) )
    {    
        @header('Location: ../index.php');
        exit;  
    }
}
else
{

}

//  Output all templates
$base->tpl->displayParsedTemplate();

// Send the output buffer to the client
if( SF_OB == TRUE)
{
    ob_end_flush();
}
?>