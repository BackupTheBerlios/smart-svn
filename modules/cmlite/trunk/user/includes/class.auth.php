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
 * User Authentication class 
 *
 * 
 *
 */
 
class auth
{ 
    /*
     * User rights
     */
    var $user_rights = FALSE;
    /*
     * User flag 
     */    
    var $is_user = FALSE;
    /*
     * Is logged flag 
     */       
    var $_is_logged = FALSE;
    
    /**
     * authentication constructor
     *
     * @param string $section Interface section 'admin' or 'public'
     */
    function auth( $section )
    {        
        if(!isset($_SESSION['id_user']) || empty($_SESSION['id_user']))
        {
            $this->_is_logged = FALSE;
        }
        
        if($section == 'admin')
        {
            $_and = 'AND rights>1';
        }
        else
        {
            $_and = '';
        }        
        
        $GLOBALS['B']->uid = (int) $_SESSION['id_user'];
        
        $sql = "SELECT
                    uid,
                    forename,
                    lastname,
                    login
                FROM
                    {$GLOBALS['B']->sys['db']['table_prefix']}user_users
                WHERE
                    uid={$GLOBALS['B']->uid}
                AND
                    status=2 {$_and}";
        
        $result = $GLOBALS['B']->db->query($sql);
        
        if($result->numRows() == 1)
        {
            $this->user_rights = (int) $_SESSION['user_rights'];
            $this->id_user     = (int) $_SESSION['id_user'];
            $this->_is_logged   = TRUE;
            
            $row = &$result->fetchRow( DB_FETCHMODE_ASSOC );
            
            $GLOBALS['B']->user_forename = stripslashes($row['forename']);
            $GLOBALS['B']->user_lastname = stripslashes($row['lastname']);
            $GLOBALS['B']->user_login    = stripslashes($row['login']);  
        }
        else
        {
            $GLOBALS['B']->user_rights = 0;
            $this->_is_logged          = FAlSE;
        }
    }  

    function isLogged()
    {
        return $this->_is_logged;
    }

    /**
     * Check login
     *
     * @param string $login
     * @param string $passwd (not md5 encoded)
     * @return mixed False on failure 
     */    
    function checklogin($login, $passwd)
    {
        $passwd = md5($passwd);
        
        $sql = "SELECT 
                    uid,
                    rights
                FROM
                    {$GLOBALS['B']->sys['db']['table_prefix']}user_users
                WHERE
                    login='{$login}'
                AND
                    passwd='{$passwd}'
                AND
                    status=2";
        
        $result = $GLOBALS['B']->db->query($sql);
     
        if($result->numRows() == 1)
        {
            $row = $result->fetchRow( DB_FETCHMODE_ASSOC );
            $GLOBALS['B']->session->set('logged_id_user',       $row['uid']);
            $GLOBALS['B']->session->set('logged_user_rights',   $row['rights']);
            return $row['rights'];
        }
        else
        {
            return FAlSE;
        }        
    }
}

?>
