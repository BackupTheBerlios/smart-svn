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
 * common_sys_setup class 
 *
 */
 
class common_sys_setup
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function common_sys_setup()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {            
        // init the success var
        $success = TRUE;
            
        // include here all stuff to get work this module:
        // creating db tables

        // The module name and version
        // this array vars were saved later by the setup handler
        // in the file /admin/modules/common/config/config.php
        //
        $this->B->conf_val['module']['common']['name']     = 'common';
        $this->B->conf_val['module']['common']['version']  = MOD_COMMON_VERSION;
        $this->B->conf_val['module']['common']['mod_type'] = 'cmlite';
        $this->B->conf_val['module']['common']['info']     = 'This is the common modul';

        if(!is_writeable( SF_BASE_DIR . 'modules/common/config' ))
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'modules/common/config';
            $success = FALSE;
        }

        if(!is_writeable( SF_BASE_DIR . 'modules/common/tmp/cache' ))
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'modules/common/tmp/cache';
            $success = FALSE;
        }

        // check if session folder is writeable
        if(!is_writeable( SF_BASE_DIR . 'modules/common/tmp/session_data' ))
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'modules/common/tmp/session_data';
            $success = FALSE;
        } 

        // create bad_words table for mysql
        if($_POST['dbtype'] == 'mysql')
        {
            // The bad words table. Words ignored by word indexer
            $sql = "CREATE TABLE IF NOT EXISTS {$this->B->conf_val['db']['table_prefix']}bad_words (
                    word varchar(255) NOT NULL default '',
                    lang varchar(4) NOT NULL default '')"; 
        
            $result = $this->B->db->query($sql);

            if (DB::isError($result))
            {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
                $success = FALSE;
            }             
        }
        
        // if noting is going wrong $success is still TRUE else FALSE
        // ex.: if creating db tables fails you must set this var to false
        return $success;  
    }    
}

?>