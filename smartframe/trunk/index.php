<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * Front Controller
 *
 */
 
/*
 * Secure include of files from this script
 */
define('SF_SECURE_INCLUDE', 1);

// Define the absolute path to SMART
//
define('SF_BASE_DIR', dirname(__FILE__) . '/');

// Include the base file
include( SF_BASE_DIR . 'smart/includes/core.inc.php' );

// Define section area
if ($_REQUEST['admin'] == '1')
{
    define('SF_SECTION', 'admin');   
}
else
{
    define('SF_SECTION', 'public');   
}

// Broadcast init event to all registered module event handlers
// see modules/xxx/actions/class.xxx_sys_init.php
$B->B( 'sys_init' );

// Directed intercepting filter event (auto_prepend)
// see smart/actions/class.system_sys_prepend.php
$B->M( MOD_SYSTEM, 'sys_prepend' );

// Directed authentication event to the module handler, 
// which takes the authentication part
// The variable SF_AUTH_MODULE must be declared in the "common"
// module event_handler.php file
$B->M( SF_AUTH_MODULE, 'sys_authenticate' );

// Logout
if ( $_REQUEST['logout'] == '1' )
{
    // each module can do clean up before logout
    // see modules/xxx/actions/class.xxx_sys_logout.php
    $B->B('sys_logout');
    
    if (SF_SECTION == 'admin')
    {
        header ( 'Location: '.SF_BASE_LOCATION.'/index.php?admin=1' );
    }
    else
    {
        header ( 'Location: '.SF_BASE_LOCATION.'/index.php' );
    }
    exit;
}

// switch to the demanded front controller flow: default=public else admin)
//
switch ( SF_SECTION )
{
    case 'admin':  
        // if an update was done this event finish the update process
        if(isset($B->system_update_flag))
        {
            // see modules/SF_BASE_MODULE/actions/class.SF_BASE_MODULE_sys_update_config.php
            $B->M( SF_BASE_MODULE, 'sys_update_config' );
            // reload admin section
            @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1');
            exit;    
        }   
        
        // get the admin view (template)
        // see smart/actions/class.system_get_admin_view.php
        include( $B->M( MOD_SYSTEM, 'get_admin_view') ); 
  
        break;
        
    default:
        // get the public view (template)
        // see smart/actions/class.system_get_admin_view.php
        include( $B->M( MOD_SYSTEM, 'get_public_view') ); 
      
        break;
}
  
// Directed intercepting filter event (auto_append)
// see smart/actions/class.system_sys_append.php
$B->M( MOD_SYSTEM, 'sys_append' );   

// Send the output buffer to the client
while (@ob_end_flush());

?>