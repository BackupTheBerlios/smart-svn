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

/*
 * The Front Controller
 *
 */

/*
 * Front Controller File Name
 */
if(!defined( SF_CONTROLLER ))
{
   define('SF_CONTROLLER', 'index.php'); 
}

/*
 * Relative path to SMART example: 'test/'
 */
if(!defined( SF_RELATIVE_PATH ))
{
   define('SF_RELATIVE_PATH', ''); 
}

/*
 * Fixed template group. example: 'test'
 */
if(!defined( SF_TPL_GROUP ))
{
   define('SF_TPL_GROUP', ''); 
}

/*
 * Template folder. example: 'test/'
 */
if(!defined( SF_TPL_FOLDER ))
{
   define('SF_TPL_FOLDER', ''); 
}

/*
 * View folder. example: 'test/'
 */
if(!defined( SF_VIEW_FOLDER ))
{
   define('SF_VIEW_FOLDER', 'view/'); 
}

/*
 * Use SMART template engine.
 */
if(!defined( SF_TEMPLATE_ENGINE ))
{
   define('SF_TEMPLATE_ENGINE', TRUE); 
}

/* #################################################### */
/* ######### Dont change any thing below !!! ########## */
/* #################################################### */

/*
/* 
 *Secure include of files from this script
 */
if(!defined( SF_SECURE_INCLUDE ))
{
    define('SF_SECURE_INCLUDE', 1);
}

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
        header ( 'Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1' );
    }
    else
    {
        header ( 'Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER );
    }
    exit;
}

// switch to the demanded front controller flow: default=public else admin)
//
switch ( SF_SECTION )
{
    case 'admin':  
        // if an update was done this event complete the update process
        if(isset($B->system_update_flag))
        {
            // see modules/SF_BASE_MODULE/actions/class.SF_BASE_MODULE_sys_update_config.php
            $B->M( SF_BASE_MODULE, 'sys_update_config', $B->sys );
            // reload admin section
            @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1');
            exit;    
        }   
        
        // get the admin view (template)
        // see smart/actions/class.system_get_admin_view.php
        include( $B->M( MOD_SYSTEM, 'get_admin_view') ); 
  
        break;
        
    default:
        // perform on the requested view class and return its object
        $view = $B->M( MOD_SYSTEM, 'get_public_view');
        // render a template ???
        if ( SF_TEMPLATE_RENDER == $view->render_template )
        {
            // get the public template
            include( $view->getTemplate() );   
        }        
        // Launch view related append filter chain
        $view->appendFilterChain();                
        break;
}

// Send the output buffer to the client
while (@ob_end_flush());

?>