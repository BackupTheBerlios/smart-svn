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
 * user_sys_init class 
 *
 */
 
class user_sys_init
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
    function user_sys_init()
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
     * Check if version number has changed and perfom additional upgarde code
     *
     * @param array $data
     */
    function perform( $data )
    {
        // Check for upgrade  
        if(MOD_USER_VERSION != (string)$this->B->sys['module']['user']['version'])
        {
            // set the new version num of this module
            $this->B->sys['module']['user']['version'] = MOD_USER_VERSION;
            $this->B->system_update_flag = TRUE;  
                
            // include here additional upgrade code
        }
 
        $dsn = $this->B->sys['option']['db_type'].'://'.$this->B->sys['option']['db_user'].':'.$this->B->sys['option']['db_passwd'].'@'.$this->B->sys['option']['db_host'].'/'.$this->B->sys['option']['db_database'];
        
        $this->B->liveuserConfig = array(
            'login'             => array('username' => 'handle', 'password' => 'passwd', 'remember' => 'remember'),
            'logout'            => array('trigger' => 'logout'),
            'cookie'            => array('name' => 'loginInfo', 'path' => '', 'domain' => '', 'lifetime' => 30),
            'authContainers'    => array(0 => array(
                                                'type' => 'DB',
                                                'dsn'  => $dsn,
                                                'authTable'     => $this->B->sys['option']['db_table_prefix'].'user_users',
                                                'loginTimeout' => 0,
                                                'expireTime'   => 3600,
                                                'idleTime'     => 1800,
                                                'allowDuplicateHandles'  => false,
                                                'passwordEncryptionMode' => 'MD5'
                                               )),
            'permContainer'     => array('type'   => 'DB_Simple',        
                                         'dsn'    => $dsn,
                                         'prefix' => $this->B->sys['option']['db_table_prefix'].'user_')
        );        
    }    
}

?>
