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
 * earchive module admin event handler
 *
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_EARCHIVE' , 'EARCHIVE');

// Version of this modul
define ( 'MOD_EARCHIVE_VERSION' , '0.1');

// register this handler                       
if (FALSE == $B->register_handler(MOD_EARCHIVE,
                           array ( 'module'        => MOD_EARCHIVE,
                                   'event_handler' => 'earchive_event_handler') ))
{
    trigger_error( 'The handler '.MOD_EARCHIVE.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function earchive_event_handler( $evt )
{
    global $B;

    switch( $evt["code"] )
    {
        case EVT_LOAD_MODULE:
            // earchive rights class
            include(SF_BASE_DIR.'/admin/modules/earchive/class.rights.php');   

            // check if the login user have rights to access this module
            // 4 or 5 required (editor or administrator)
            if(FALSE == earchive_rights::ask_access_to_list())
            {
                @header('Location: index.php');
                exit;
            }
            
            // load this module
            include(SF_BASE_DIR . '/admin/modules/earchive/module_loader.php');           
            break;             
        case EVT_INIT: 
            // check for install or upgrade
            if (MOD_EARCHIVE_VERSION != (string)$B->sys['module']['earchive']['version'])
            {
                $B->setup_error = array();
                $B->conf_val = &$B->sys;
                include(SF_BASE_DIR . '/admin/modules/earchive/_setup.php'); 
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
            if(!empty($_POST['earchive_rebuild_index']))
            {
                // the earchive class
                include_once SF_BASE_DIR . '/admin/modules/earchive/class.earchive.php';
                $earchiver = & new earchive; 
                
                include_once(SF_BASE_DIR.'/admin/include/class.sfWordIndexer.php');
                $word_indexer = & new word_indexer();
                
                $fields = array('mid','lid','subject','body','sender');
                $result = $earchiver->get_all_messages( $fields );
                
                if(is_object($result))
                {
                    while($row = &$result->FetchRow( DB_FETCHMODE_ASSOC ))
                    {
                        $content  = '';
                        $content .= stripslashes($row['sender']);
                        $content .= stripslashes($row['subject']);
                        $content .= stripslashes($row['body']);
                        
                        $word_indexer->indexing_words( $content, 'earchive_words_crc32', array('mid' => $row['mid'], 'lid' => $row['lid']), TRUE);      
                    }
                }                
            }
            break;             
        case EVT_GET_OPTIONS:  
            // get earchive options template 
            // to include in the option module
            $B->mod_option[] = SF_BASE_DIR.'/admin/modules/earchive/templates/option.tpl.php';
            break;                
        case EVT_SETUP:       
            if( count($base->tmp_error) == 0 )
            {
                include(SF_BASE_DIR.'/admin/modules/earchive/_setup.php'); 
            }
            break;            
    }
}

?>
