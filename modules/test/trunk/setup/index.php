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
 * The setup file for this module group
 *
 */
 
// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Init error array
$B->setup_error = array();

// launch setup
if( $_POST['do_setup'] )
{
    // Send a setup message to the system handler
    $success = $B->M( MOD_SYSTEM,           EVT_SETUP );

    // Send a setup message to the common handler
    if($success == TRUE)    
        $success = $B->M( MOD_COMMON,        EVT_SETUP );
    
    // Send a setup message to the entry handler
    if($success == TRUE)    
        $success = $B->M( MOD_ENTRY,        EVT_SETUP );
    
    // Send a setup message to the test handler
    if($success == TRUE)
        $success = $B->M( MOD_TEST,         EVT_SETUP );
    
    // Send a setup message to the option handler
    if($success == TRUE)
        $success = $B->M( MOD_OPTION,       EVT_SETUP );
        
    // check on errors before proceed
    if( $success == TRUE )
    {   
        // write the system config file
        $B->conf_val['info']['status'] = TRUE;
        $B->conf->setConfigValues( $B->conf_val );
        $B->conf->writeConfigFile( "config_system.xml.php", array('filetype' => 'xml', 'mode' => 'pretty') );
        
        @header('Location: '.SF_BASE_LOCATION.'/admin/index.php');
        exit;  
    }
}

// Include the setup template
include(  SF_BASE_DIR . '/admin/modules/setup/index.tpl.php' ); 

// Send the output buffer to the client
if( SF_OB == TRUE)
{
    ob_end_flush();
}

// Basta
exit;

?>