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
        $this->B->conf_val['module']['common']['info']     = 'This is the common module';
        
        $this->B->conf_val['db']['type']         = $data['dbtype'];
        $this->B->conf_val['db']['name']         = $data['dbname'];
        $this->B->conf_val['db']['host']         = $data['dbhost'];
        $this->B->conf_val['db']['user']         = $data['dbuser'];
        $this->B->conf_val['db']['passwd']       = $data['dbpasswd'];
        $this->B->conf_val['db']['table_prefix'] = $data['dbtablesprefix'];

        // Load db manager to create database
        if(!empty($data['dbcreate']))
        {
            $this->B->dbmanager = $this->B->db->loadModule('manager', $this->B->db->dbmanager); 

            if (MDB2::isError($this->B->dbmanager)) 
            {
                trigger_error($this->B->dbmanager->getMessage()."\n\nINFO: ".$this->B->dbmanager->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            }             

            $success = $this->_create_db( $data['dbname'] );   
        }
        
        // set the created database
        if( $success == TRUE )
        {
            $this->B->db->setDatabase( $data['dbname'] );
        }
        
        return $success;  
    }  
    /**
     * Create database
     *
     * @param string $dbname Database name
     * @access privat
     */    
    function _create_db( $dbname )
    {        
        $result = $this->B->dbmanager->createDatabase( $dbname );

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = "Can not create database: ".$result->getMessage()."\n\nINFO: ".$_result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            return FALSE;
        } 
        
        return TRUE;
    }    
}

?>
