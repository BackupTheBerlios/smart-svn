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
 * Public test module event handler
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_NAVIGATION' , 'NAVIGATION');

// define a handler function which can be used from within a template
define ( 'EVT_NAVIGATION_GET' ,      'NAVIGATION_GET');


// register this handler                       
if (FALSE == $B->register_handler( MOD_NAVIGATION,
                           array ( 'module'        => MOD_NAVIGATION,
                                   'event_handler' => 'navigation_event_handler') ))
{
    trigger_error( 'The handler '.MOD_NAVIGATION_GET.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
} 

// The handler function
function navigation_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {
        case EVT_NAVIGATION_GET:
        
            // get var name defined in the public template to store the result
            $_result = & $GLOBALS['B']->$evt['data']['var']; 
            
            // The navigation array
            $_result = array( 'Counter'  => 'counter',
                              'Contact'  => 'contact',
                              'Site Map' => 'sitemap');
            
            break;              
    }
}

?>
