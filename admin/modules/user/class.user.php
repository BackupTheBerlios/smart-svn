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
    /**
     * get users data
     *
     * @param array $fields Field names of the user db table
     * @param int $rights Get user with specific rights (FALSE get all)
     * @return array Users data 
     */ 
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
        
        $result = $GLOBALS['B']->db->query($sql);
        
        $data = array();
        while($row = $GLOBALS['B']->db->getRow($result))
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

    /**
     * get user data
     *
     * @param int $uid User id
     * @param array $fields Field names of the user db table
     * @return array User data 
     */     
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
        
        $result = $GLOBALS['B']->db->query($sql);
        
        return $GLOBALS['B']->db->getRow($result);
    } 
    
    /**
     * add user
     *
     * @param array $data associative array of user data
     */     
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
        
        $GLOBALS['B']->db->query($sql);
    } 
    /**
     * update user
     *
     * @param int $uid User id
     * @param array $data associative array of user data
     */
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
        
        $GLOBALS['B']->db->query($sql);
        
        return TRUE;
    } 

    /**
     * delete user
     *
     * @param int $uid User id
     */
    function delete_user( $uid )
    {
        $sql = "
            DELETE FROM 
                user_users
            WHERE
                uid={$uid}";
        
        $GLOBALS['B']->db->query($sql);
    }

    /**
     * check if login exist
     *
     * @param string $login User login
     * @return int Number of logins
     */    
    function login_exists($login)
    {
        $sql = "
            SELECT
                uid
            FROM
                user_users
            WHERE
                login='{$login}'";
        
        $result = $GLOBALS['B']->db->query($sql);
        return $GLOBALS['B']->db->numRows($result);    
    }
}

?>
