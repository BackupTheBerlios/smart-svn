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
 * user_sys_setup class 
 *
 */
 
class user_sys_setup
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
    function user_sys_setup()
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
        $success = $this->_create_tables();
            
        if($success == TRUE)
        {
            $success = $this->_create_user( $data );
        }            
    
        $this->B->conf_val['module']['user']['name']     = 'user';
        $this->B->conf_val['module']['user']['version']  = MOD_USER_VERSION;
        $this->B->conf_val['module']['user']['mod_type'] = 'lite';
        $this->B->conf_val['module']['user']['info']     = 'This is leader module of this module group. Author: Armand Turpel <smart AT open-publisher.net>';  
    
        $this->B->conf_val['option']['user']['allow_register']  = TRUE;
        $this->B->conf_val['option']['user']['register_type']   = 'auto';
        
        return $success;
    }

    /**
     * Create admin user
     *
     * @param array $data
     * @access privat
     */    
    function _create_user( $data )
    {
        $uid = $this->B->db->nextId($this->B->conf_val['db']['table_prefix'].'user_users');

        if (MDB2::isError($uid)) 
        {
            trigger_error($uid->getMessage()."\n".$uid->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            return FALSE;
        }    
    
        $sql = '
            INSERT INTO 
                '.$this->B->conf_val['db']['table_prefix'].'user_users
                (uid,forename,lastname,email,login,passwd,status,rights)
            VALUES
                ('.$uid.',
                 "'.$this->B->db->escape($data['username']).'",
                 "'.$this->B->db->escape($data['userlastname']).'",
                 "admin@foo.com",
                 "'.$this->B->db->escape($data['userlogin']).'",
                 "'.$this->B->db->escape(md5($data['userpasswd1'])).'",
                 2,
                 5)';
         
        $result = $this->B->db->query($sql);

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            return FALSE;
        }
       
        return TRUE;    
    }
    /**
     * Create user tables
     *
     * @access privat
     */     
    function _create_tables()
    {
        $table  = $this->B->conf_val['db']['table_prefix'] . 'user_users';
        
        $fields = array( 'uid'      => array( 'type'     => 'integer', 
                                              'notnull'  => 1,
                                              'default'  => 0 ), 
                         'status'   => array( 'type'     => 'integer',
                                              'notnull'  => 1,
                                              'default'  => 1 ), 
                         'rights'   => array( 'type'     => 'integer',
                                              'notnull'  => 1,
                                              'default'  => 1 ),
                         'login'    => array( 'type'     => 'text',
                                              'length'   => 30),
                         'passwd'   => array( 'type'     => 'text',
                                              'length'   => 32),
                         'forename' => array( 'type'     => 'text',
                                              'length'   => 50), 
                         'lastname' => array( 'type'     => 'text',
                                              'length'   => 50),
                         'email'    => array( 'type'     => 'text',
                                              'length'   => 1024) 
                       );
                       
        $result = $this->B->dbmanager->createTable( $table, $fields );

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            return FALSE;
        } 
        
        $index = array( 'fields' => array( 'uid'    => array() ) );
        $result = $this->B->dbmanager->createIndex( $table, 'uid', $index);

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        $index = array( 'fields' => array( 'status' => array() ) ); 
        $result = $this->B->dbmanager->createIndex( $table, 'status', $index);    

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        $index = array( 'fields' => array( 'rights' => array() ) ); 
        $result = $this->B->dbmanager->createIndex( $table, 'rights', $index);   
        
        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        } 
        
        $table  = $this->B->conf_val['db']['table_prefix'] . 'user_registered';
        
        $fields = array( 'uid'      => array( 'type'     => 'integer', 
                                              'notnull'  => 1,
                                              'default'  => 0 ), 
                         'md5_str'  => array( 'type'     => 'text',
                                              'length'   => 32),
                         'reg_date' => array( 'type'     => 'time') 
                       );
                       
        $result = $this->B->dbmanager->createTable( $table, $fields );

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
        } 
        
        $result = $this->B->dbmanager->createSequence( $this->B->conf_val['db']['table_prefix'] . 'user_users' );

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
        } 
        
        return TRUE;
    }
}

?>
