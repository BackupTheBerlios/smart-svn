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
                {$GLOBALS['B']->sys['db']['table_prefix']}user_users
                {$where}
            ORDER BY
                rights DESC, lastname ASC";
        
        $result = $GLOBALS['B']->conn->Execute($sql);
        
        $data = array();
        while($row = $result->FetchRow())
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
                {$GLOBALS['B']->sys['db']['table_prefix']}user_users
            WHERE
                uid={$uid}";
        
        return $GLOBALS['B']->conn->getRow($sql);
    } 
    
    /**
     * add user
     *
     * @param array $data associative array of user data
     * @return bool true or false
     */     
    function add_user( $data )
    {
        if($this->login_exists($data['login']) > 0)
        {
            return FALSE;
        }
        
        $sql = '
            INSERT INTO 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'user_users
                (forename,lastname,email,login,passwd,status,rights)
            VALUES
                ('.$data['forename'].',
                 '.$data['lastname'].',
                 '.$data['email'].',
                 '.$data['login'].',
                 '.$data['passwd'].',
                 '.$data['status'].',
                 '.$data['rights'].')';
        
        return $GLOBALS['B']->conn->Execute($sql);
    } 
    /**
     * update user
     *
     * @param int $uid User id
     * @param array $data associative array of user data
     */
    function update_user( $uid, $data )
    {
        $set = '';
        $comma = '';
        
        foreach($data as $key => $val)
        {
            $set .= $comma.$key.'='.$val;
            $comma = ',';
        }
        
        $sql = '
            UPDATE 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'user_users
            SET
                '.$set.'
            WHERE
                uid='.$uid;
        
        return $GLOBALS['B']->conn->Execute($sql);
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
                {$GLOBALS['B']->sys['db']['table_prefix']}user_users
            WHERE
                uid={$uid}";
        
        return $GLOBALS['B']->conn->Execute($sql);
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
                '.$GLOBALS['B']->sys['db']['table_prefix'].'user_users
            WHERE
                login='.$login;
        
        $result = $GLOBALS['B']->conn->Execute($sql);
        return $result->RecordCount();    
    }
}

?>
