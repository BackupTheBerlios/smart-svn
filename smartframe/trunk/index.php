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

// switch to the demanded front controller flow: default=public else admin)
//
switch ( $_REQUEST['admin'] )
{
    case '1': 
        // Define section area
        define('SF_SECTION', 'admin');   

        // switch to the public page from within the admin page
        if($_REQUEST['view'] == 'public')
        {
            // reload public section
            @header('Location: '.SF_BASE_LOCATION.'/index.php');
            exit;         
        }

        // Directed authentication event to the module handler, 
        // which takes the authentication part
        $B->M( SF_AUTH_MODULE, 'sys_authenticate' );

        // Logout
        if ( (int)$_REQUEST['logout'] == 1 )
        {
            // each module can do clean ups before logout
            $B->B('sys_logout');
            header ( 'Location: '.SF_BASE_LOCATION.'/index.php' );
            exit;
        }

        // Broadcast init event to all registered event handlers
        $B->B( 'sys_init' );
        
        // if an update was done this event finish the update process
        if(isset($B->system_update_flag))
        {
            $B->M( SF_BASE_MODULE, 'sys_finish_update' );
            // reload admin section
            @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1');
            exit;    
        }   
        
        // get the admin view
        include( $B->M( MOD_SYSTEM, 'get_admin_view') ); 
        
        // add to the URLs and forms the "admin" variable.
        @output_add_rewrite_var('admin', '1');
        
        break;
        
    default:
        // Define section area
        define('SF_SECTION', 'public'); 

        // Directed intercepting filter event (auto_prepend)
        $B->M( MOD_SYSTEM, 'sys_prepend' );

        // Directed authentication event to the module handler, 
        // which takes the authentication part
        $B->M( SF_AUTH_MODULE, 'sys_authenticate' );

        // Logout
        if ( (int)$_REQUEST['logout'] == 1 )
        {
            // each module can do clean ups before logout
            $B->B('sys_logout');
            header ( 'Location: '.SF_BASE_LOCATION.'/index.php' );
            exit;
        }

        // Broadcast init event to all registered event handlers
        $B->B( 'sys_init' );
        
        // get the public view (template)
        include( $B->M( MOD_SYSTEM, 'get_public_view') ); 
        
        // Directed intercepting filter event (auto_append)
        $B->M( MOD_SYSTEM, 'sys_append' );        
        
        break;
}

// Send the output buffer to the client
ob_end_flush();

?>