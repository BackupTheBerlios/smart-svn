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
 * The user class 
 *
 * 
 *
 */
 
class user
{   
    function get_users( $fields, $rights = FALSE )
    {
        if($rights != FALSE)
        {
            $_where = "WHERE rights={$rights}";
        }
        
        $comma = '';
        foreach ($fields as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                user_users
                {$where}
            ORDER BY
                rights DESC, lastname ASC";
        
        $result = $GLOBALS['B']->dbdata->query($sql);
        
        $data = array();
        while($row = $GLOBALS['B']->dbdata->getRow($result))
        {
            $tmp = array();
            foreach($fields as $f)
            {
                $tmp[$f] = stripslashes($row[$f]);
            }
            $data[] = $tmp;
        }
        return $data;
    }
    
    function get_user( $uid, $fields )
    {
        $comma = '';
        foreach ($fields as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                user_users
            WHERE
                uid={$uid}";
        
        $result = $GLOBALS['B']->dbdata->query($sql);
        
        return $GLOBALS['B']->dbdata->getRow($result);
    } 
    
    function add_user( $data )
    {
        if($this->login_exists($data['login']) > 0)
        {
            return FALSE;
        }
        
        $sql = "
            INSERT INTO 
                user_users
                (forename,lastname,email,login,passwd,status,rights)
            VALUES
                ('{$data['forename']}',
                 '{$data['lastname']}',
                 '{$data['email']}',
                 '{$data['login']}',
                 '{$data['passwd']}',
                 '{$data['status']}',
                 '{$data['rights']}')";
        
        $GLOBALS['B']->dbdata->query($sql);
        
        return TRUE;
    } 

    function update_user( $uid, $data )
    {
        if($this->login_exists($data['login']) > 0)
        {
            return FALSE;
        }
        
        $set = '';
        $comma = '';
        
        foreach($data as $key => $val)
        {
            $set .= $comma.$key."='".$val."'";
            $comma = ',';
        }
        
        $sql = "
            UPDATE 
                user_users
            SET
                {$set}
            WHERE
                uid={$uid}";
        
        $GLOBALS['B']->dbdata->query($sql);
        
        return TRUE;
    } 

    function delete_user( $uid )
    {
        $sql = "
            DELETE FROM 
                user_users
            WHERE
                uid={$uid}";
        
        $GLOBALS['B']->dbdata->query($sql);
    }
    
    function login_exists($login)
    {
        $sql = "
            SELECT
                uid
            FROM
                user_users
            WHERE
                login='{$login}'";
        
        $result = $GLOBALS['B']->dbdata->query($sql);
        return $GLOBALS['B']->dbdata->numRows($result);    
    }
}

?>
