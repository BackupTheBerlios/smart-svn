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
 * System event handler
 *
 *
 */

// Check if this file is included in the SMART environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on'. __FILE__);
}

// Name of the event handler
define( 'SF_EVT_HANDLER_SYSTEM' , 'system' );

// register this handler                       
if (FALSE == $base->event->register_handler( 
                           SF_EVT_HANDLER_SYSTEM,
                           array ( 'module'        => SF_EVT_HANDLER_SYSTEM,
                                   'event_handler' => 'system_event_handler') ))
{
    patErrorManager::raiseError( 'handler',
                                 'Handler exist', 
                                 'The handler '.SF_EVT_HANDLER_SYSTEM.' exist on '.__FILE__.' '.__LINE__  );        
}

// The handler function
function system_event_handler( $evt )
{
    global $base;
    
    switch( $evt["code"] )
    {            
        case SF_EVT_INIT:
            include_once(SF_BASE_DIR.'/admin/include/_init_system.php');          
            break; 
        case SF_EVT_LOGOUT:  
            break;    
        case SF_EVT_SETUP:         
            include_once(SF_BASE_DIR.'/admin/include/_setup.php');          
            break;             
        case SF_EVT_SETUP_FINISH:   
            $base->conf_rel_info->setConfigValue( 'info.status', 'ready' );
            $base->conf_rel_info->writeConfigFile('config_release_info.xml', 'xml', array('mode' => 'pretty'));          
            break;                
    }
}

?>
