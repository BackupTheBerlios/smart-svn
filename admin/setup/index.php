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
include( SF_BASE_DIR.'/admin/setup/setup.inc.php' );

// launch setup
if( $_POST['do_setup'] )
{
    // Send a setup message to all registered handlers
    $B->B( EVT_SETUP );
    
    if(is_object($B->dbdata))
        $B->dbdata->close();
    if(is_object($B->dbsystem))    
        $B->dbsystem->close();
    if(is_object($B->dbsession))    
        $B->dbsession->close(); 
        
    // if there are errors
    if( count($B->setup_error) == 0 )
    {      
        @header('Location: ../index.php');
        exit;  
    }
}

//  Output all templates
// set the setup template
include(  SF_BASE_DIR . '/admin/setup/index.tpl.php' ); 

// Send the output buffer to the client
if( SF_OB == TRUE)
{
    ob_end_flush();
}
?>