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
 * Option module public event handler
 *
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'SF_EVT_HANDLER_OPTION' , 'option');

// register this handler                       
if (FALSE == $base->event->register_handler( 
                           SF_EVT_HANDLER_OPTION,
                           array ( 'module'        => SF_EVT_HANDLER_OPTION,
                                   'event_handler' => 'option_event_handler') ))
{
    patErrorManager::raiseError( 'handler',
                                 'Handler exist', 
                                 'The handler '.SF_EVT_HANDLER_OPTION.' exist: '.__FILE__.' '.__LINE__  );        
}

// The handler function
function option_event_handler( $evt )
{
    global $base;

    switch( $evt['code'] )
    {
        case SF_EVT_LOAD_INIT_OPTION:
            //  parse global config options file
            $base->conf->loadCachedConfig( 'config_options.xml' );
            //  get the db name set
            $base->db_connect = $base->conf->getConfigValue('option.db');
            //  get the public template folder name
            $base->tpl_folder = $base->conf->getConfigValue('option.templates_folder');
            // get charset
            $base->charset = $base->conf->getConfigValue('option.charset');
            $base->tpl->addVar( SF_SECTION, 'charset', $base->charset );
            //  parse config db file
            $base->conf->loadCachedConfig( 'config_db_connect.xml.php' );
            //  get all config db values
            $base->db_data = $base->conf->getConfigValue( 'db.' . $base->db_connect );
            break;                  
        case SF_EVT_INIT:    
            break; 
        case SF_EVT_LOGOUT:  
            break;             
    }
}

?>
