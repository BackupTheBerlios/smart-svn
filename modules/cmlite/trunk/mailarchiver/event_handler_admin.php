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
 * MAILARCHIVER module admin event handler
 *
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_MAILARCHIVER' , 'MAILARCHIVER');

// Version of this modul
define ( 'MOD_MAILARCHIVER_VERSION' , '0.1');

// register this handler                       
if (FALSE == $B->register_handler(MOD_MAILARCHIVER,
                           array ( 'module'        => MOD_MAILARCHIVER,
                                   'event_handler' => 'mailarchiver_event_handler') ))
{
    trigger_error( 'The handler '.MOD_MAILARCHIVER.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function mailarchiver_event_handler( $evt )
{
    global $B;

    switch( $evt["code"] )
    {
        case EVT_LOAD_MODULE:
            // mailarchiver rights class
            include(SF_BASE_DIR.'/admin/modules/mailarchiver/class.rights.php');   

            // check if the login user have rights to access this module
            // 4 or 5 required (editor or administrator)
            if(FALSE == mailarchiver_rights::ask_access_to_list())
            {
                @header('Location: index.php');
                exit;
            }
            
            // load this module
            include(SF_BASE_DIR . '/admin/modules/mailarchiver/module_loader.php');           
            break;             
        case EVT_INIT: 
            // check for install or upgrade
            if (MOD_MAILARCHIVER_VERSION != (string)$B->sys['module']['mailarchiver']['version'])
            {
                $B->setup_error = array();
                $B->conf_val = &$B->sys;
                include(SF_BASE_DIR . '/admin/modules/mailarchiver/_setup.php'); 
                $B->conf->setConfigValues( $B->conf_val );
                $B->conf->writeConfigFile( "config_system.xml.php", array('filetype' => 'xml', 'mode' => 'pretty') );               
                if( count($B->setup_error) > 0 )
                    trigger_error( "Setup/upgrade error: ".var_export($B->setup_error, TRUE)."\n".__FILE__."\n".__LINE__, E_USER_ERROR  );
            }       
            break; 
        case EVT_LOGOUT:  
            break; 
        case EVT_SET_OPTIONS:  
            // set user options 
            // this event comes from the option module (module_loader.php)
            if(!empty($_POST['mailarchiver_rebuild_index']))
            {
                // the mailarchiver class
                include_once SF_BASE_DIR . '/admin/modules/mailarchiver/class.mailarchiver.php';
                $marchiver = & new mailarchiver; 
                
                include_once(SF_BASE_DIR.'/admin/include/class.sfWordIndexer.php');
                $word_indexer = & new word_indexer();
                
                $fields = array('mid','subject','body','sender');
                $result = $marchiver->get_all_messages( $fields );
                
                if(is_object($result))
                {
                    while($row = &$result->FetchRow( DB_FETCHMODE_ASSOC ))
                    {
                        $content = '';
                        $content .= stripslashes($row['sender']);
                        $content .= stripslashes($row['subject']);
                        $content .= stripslashes($row['body']);
                        $mid = $row['mid'];
                        
                        $word_indexer->indexing_words( $content, 'mailarchiver_words_crc32', 'mid', $mid, TRUE);      
                    }
                }                
            }
            break;             
        case EVT_GET_OPTIONS:  
            // get mailarchiver options template 
            // to include in the option module
            $B->mod_option[] = SF_BASE_DIR.'/admin/modules/mailarchiver/templates/option.tpl.php';
            break;                
        case EVT_SETUP:       
            if( count($base->tmp_error) == 0 )
            {
                include(SF_BASE_DIR.'/admin/modules/mailarchiver/_setup.php'); 
            }
            break;            
    }
}

?>
