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
 * user_upgrade class 
 *
 */
 
class user_upgrade
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
    function user_upgrade()
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
     * upgarde user tables
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data )
    {
        $this->B->$data['error'] = FALSE;
        $error                   = & $this->B->$data['error'];

        if(!is_object($this->B->dbmanager))
        {
            $this->B->dbmanager = $this->B->db->loadModule('manager', $this->B->db->dbmanager); 

            if (MDB2::isError($this->B->dbmanager)) 
            {
                trigger_error($this->B->dbmanager->getMessage()."\n\nINFO: ".$this->B->dbmanager->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                return FALSE;
            }  
        }
        
        if(version_compare( '0.5', (string)$this->B->sys['module']['earchive']['version'], '>'))
        {
            $this->B->dbmanager->alterTable($this->B->sys['db']['table_prefix'].'earchive_seq_add_attach_seq', array('name' => $this->B->sys['db']['table_prefix'].'earchive_attach_seq'));
            $this->B->dbmanager->alterTable($this->B->sys['db']['table_prefix'].'earchive_seq_add_list_seq', array('name' => $this->B->sys['db']['table_prefix'].'earchive_lists_seq'));
            $this->B->dbmanager->alterTable($this->B->sys['db']['table_prefix'].'earchive_seq_add_message_seq', array('name' => $this->B->sys['db']['table_prefix'].'earchive_messages_seq'));
        }
               
        return TRUE;
    } 
}

?>
