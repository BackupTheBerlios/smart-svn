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
define ( 'MOD_TEST' , 'TEST');

// define a handler function which can be used from within a template
define ( 'EVT_TEST_COUNTER' ,      'TEST_COUNTER');


// register this handler                       
if (FALSE == $B->register_handler(MOD_TEST,
                           array ( 'module'        => MOD_TEST,
                                   'event_handler' => 'test_event_handler') ))
{
    trigger_error( 'The handler '.MOD_TEST.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
} 

// The handler function
function test_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {
        case EVT_TEST_COUNTER:
            // get var name defined in the public template to store the result
            $_result = & $GLOBALS['B']->$evt['data']['var']; 
            
            // the result must be an array
            $_result = array();
            
            // check if start/end counter are defined else set default values
            if(empty($evt['data']['start_counter']))
                $evt['data']['start_counter'] = 0;
            if(empty($evt['data']['end_counter']))
                $evt['data']['end_counter'] = 10;
                
            // assign counter vars
            for($i=$evt['data']['start_counter'];$i<=$evt['data']['end_counter'];$i++)
                $_result[] = $i;
            break;               
    }
}

?>
