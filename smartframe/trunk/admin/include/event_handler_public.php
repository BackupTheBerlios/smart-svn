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
    die('No Permission on '. __FILE__);
}

// Name of the event handler
define ( 'MOD_SYSTEM' , 'SYSTEM' );

// System functions definitions
define ( 'SF_EVT_START_OUTPUT_CACHE' , 'SYSTEM1' );
define ( 'SF_EVT_END_OUTPUT_CACHE' ,   'SYSTEM2' );

// register this handler                       
if (FALSE == $B->register_handler(MOD_SYSTEM,
                           array ( 'module'        => MOD_SYSTEM,
                                   'event_handler' => 'system_event_handler') ))
{
    trigger_error( 'The handler '.MOD_SYSTEM.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function system_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {            
        case SF_EVT_INIT:                     
            break; 
        case SF_EVT_START_OUTPUT_CACHE: 
            require_once(SF_BASE_DIR.'/admin/lib/PEAR/Cache/Lite/Output.php');
            
            if(empty($evt['data']['lifetime']))
                $evt['data']['lifetime'] = 3600;
                
            $options = array(
                'cacheDir' => SF_BASE_DIR.'/admin/tmp/cache/',
                'lifeTime' => $evt['data']['lifetime']
            ); 
            
            $B->output_cache = & new Cache_Lite_Output($options);
            
            if ($B->output_cache->start($evt['data']['id'])) 
            {
                // Send the output buffer to the client
                if ( SF_OB == TRUE)
                {
                    ob_end_flush();
                } 
                exit;            
            } 

            break; 
        case SF_EVT_END_OUTPUT_CACHE: 
            // Cache the output content if cache is actif 
            if(is_object($B->output_cache))
                $B->output_cache->end();
            break;             
        case SF_EVT_LOGOUT:  
            break;               
    }
}

?>
