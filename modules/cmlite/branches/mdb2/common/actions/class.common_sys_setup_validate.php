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
 * common_sys_setup_validate class 
 *
 */
 
class common_sys_setup_validate
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
    function common_sys_setup_validate()
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
     * Check if the databse server connection is valide
     *
     * @param array $data
     */
    function perform( $data )
    {            
        // init the success var
        $success = TRUE;

        if(!is_writeable( SF_BASE_DIR . 'modules/common/config' ))
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'modules/common/config';
            $success = FALSE;
        }
           
        $this->B->dsn = array( 'phptype'  => $data['dbtype'],
                               'username' => $data['dbuser'],
                               'password' => $data['dbpasswd'],
                               'hostspec' => $data['dbhost']);

        if(empty($data['dbcreate']))
        {
            $this->B->dsn['database'] = $data['dbname'];
        }

        $this->B->db = & MDB2::connect( $this->B->dsn );
        
        if (MDB2::isError($this->B->db)) 
        {
            trigger_error($this->B->db->getMessage()."\n".$this->B->db->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $this->B->db->getMessage()."\n".$this->B->db->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            return FALSE;
        }        

        return TRUE;  
    }    
}

?>
