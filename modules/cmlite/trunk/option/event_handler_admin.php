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
 * Option module admin event handler
 *
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_OPTION' , 'OPTION');

// Version of this modul
define ( 'MOD_OPTION_VERSION' , '0.1');

// register this handler                       
if (FALSE == $B->register_handler( 
                           MOD_OPTION,
                           array ( 'module'        => MOD_OPTION,
                                   'event_handler' => 'option_event_handler') ))
{
    trigger_error( 'The handler '.MOD_OPTION.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function option_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {
        case EVT_SETUP:
            $success = TRUE;
            // The module name and version
            $B->conf_val['module']['option']['name']    = 'option';
            $B->conf_val['module']['option']['version'] = MOD_OPTION_VERSION;
            $B->conf_val['module']['option']['info']    = '';
            // Set some options
            $B->conf_val['option']['tpl'] = SF_DEFAULT_TEMPLATE_GROUP;
            $B->conf_val['option']['url'] = SF_BASE_LOCATION;
            $B->conf_val['option']['site_title'] = 'Site title';
            $B->conf_val['option']['site_desc'] = 'My first site';
            $B->conf_val['option']['email'] = 'admin@foo.com';
            $B->conf_val['option']['charset'] = $_POST['charset'];
    
            // create bad_words table for mysql or sqlite
            if($_POST['dbtype'] == 'mysql')
            {
                // The bad words table. Words ignored by word indexer
                $sql = "CREATE TABLE IF NOT EXISTS {$B->conf_val['db']['table_prefix']}bad_words (
                            word varchar(255) NOT NULL default '',
                            lang varchar(4) NOT NULL default '')"; 
                
            }
            elseif($_POST['dbtype'] == 'sqlite')
            {
                // delete the bad_words table if it exist
                $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='bad_words'";
                $result = $B->db->query($sql);

                if (DB::isError($result))
                {
                    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                    $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
                    $success = FALSE;
                }

                if($result->numRows() == 1)
                {
                    $sql = "DROP TABLE bad_words";
                    $result = $B->db->query($sql);

                    if (DB::isError($result))
                    {
                        trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                        $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
                        $success = FALSE;
                    }
                }            
                // The bad words table. Words ignored by word indexer
                $sql = "CREATE TABLE bad_words (
                            word varchar(255) NOT NULL default '',
                            lang varchar(4) NOT NULL default '')"; 
            
            }
            
            $result = $B->db->query($sql);

            if (DB::isError($result))
            {
                    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                    $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
                    $success = FALSE;
            }     
            return $success;
            break;      
        case EVT_LOAD_MODULE:
            include(SF_BASE_DIR.'/admin/modules/option/module_loader.php');           
            break;             
        case EVT_INIT: 
            // check for install or upgrade
            if (MOD_OPTION_VERSION != $B->sys['module']['option']['version'])
            {
                // 
            }         
            break; 
        case EVT_LOGOUT:  
            break;             
    }
}

?>
