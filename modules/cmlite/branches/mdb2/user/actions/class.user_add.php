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
 * user_add class 
 *
 */
 
class user_add
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
    function user_add()
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
     * add user
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data )
    {
        $this->B->$data['error'] = FALSE;
        $error                   = & $this->B->$data['error'];
        
        if($this->login_exists($data['user_data']['login']) > 0)
        {
            $error = 'Login exists';
            return FALSE;
        }

        $uid = $this->B->db->nextId($this->B->sys['db']['table_prefix'].'user_seq_add_user');

        if (MDB2::isError($uid)) 
        {
            trigger_error($uid->getMessage()."\n".$uid->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $error = 'Unexpected error';
            return FALSE;
        }
        
        $sql = '
            INSERT INTO 
                '.$this->B->sys['db']['table_prefix'].'user_users
                (uid,forename,lastname,email,login,passwd,status,rights)
            VALUES
                ('.$uid.',
                 '.$data['user_data']['forename'].',
                 '.$data['user_data']['lastname'].',
                 '.$data['user_data']['email'].',
                 '.$data['user_data']['login'].',
                 '.$data['user_data']['passwd'].',
                 '.$data['user_data']['status'].',
                 '.$data['user_data']['rights'].')';
         
        $res = $this->B->db->query($sql);

        if (MDB2::isError($res)) 
        {
            trigger_error($res->getMessage()."\n".$res->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $error = 'Unexpected error';
            return FALSE;
        }
       
        return $uid;
    } 
    /**
     * check if login exist
     *
     * @param string $login User login
     * @return int Number of logins
     */    
    function login_exists($login)
    {
        $sql = '
            SELECT
                uid
            FROM
                '.$this->B->sys['db']['table_prefix'].'user_users
            WHERE
                login='.$login;
        
        $result = &$this->B->db->query($sql);
        
        return $result->numRows();    
    }    
}

?>
