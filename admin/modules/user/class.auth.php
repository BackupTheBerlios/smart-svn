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
 * Auth Object class 
 *
 * 
 *
 */
 
class auth
{   
    /**
     * authentication constructor
     *
     * @return bool 
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
                    user_users
                WHERE
                    uid={$GLOBALS['B']->uid}
                AND
                    status=2 {$_and}";
                    
        $result = $GLOBALS['B']->dbdata->query($sql);
        
        if($GLOBALS['B']->dbdata->numRows($result) == 1)
        {
            $GLOBALS['B']->user_rights = (int) $_SESSION['user_rights'];
            $this->is_user = TRUE;
        }
        else
        {
            $GLOBALS['B']->user_rights = 0;
            $this->is_user = FAlSE;
        }
    }  
    
    function checklogin($login, $passwd)
    {
        $passwd = md5($passwd);
        
        $sql = "SELECT 
                    uid,
                    rights
                FROM
                    user_users
                WHERE
                    login='{$login}'
                AND
                    passwd='{$passwd}'
                AND
                    status=2";
        
        $result = $GLOBALS['B']->dbdata->query($sql);
        
        if($GLOBALS['B']->dbdata->numRows($result) == 1)
        {
            $row = $GLOBALS['B']->dbdata->getRow($result);
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
