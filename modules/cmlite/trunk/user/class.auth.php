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
    /**
     * authentication constructor
     *
     * @param string $section Interface section 'admin' or 'public'
     */
    function auth( $section )
    {        
        if(!isset($_SESSION['id_user']) || empty($_SESSION['id_user']))
        {
            $this->is_user = FALSE;
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
                    uid
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
            $this->is_user     = TRUE;
        }
        else
        {
            $GLOBALS['B']->user_rights = 0;
            $this->is_user             = FAlSE;
        }
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
            $GLOBALS['B']->session->set('id_user', $row['uid']);
            $GLOBALS['B']->session->set('user_rights', $row['rights']);
            return $row['rights'];
        }
        else
        {
            return FAlSE;
        }        
    }
}

?>
