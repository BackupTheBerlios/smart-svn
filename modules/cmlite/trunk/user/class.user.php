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
        
        $result = $GLOBALS['B']->db->query($sql);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage(), E_USER_ERROR);
        }        
        
        $data = array();
        
        if(is_object($result))
        {        
            while($row = &$result->fetchRow( DB_FETCHMODE_ASSOC ))
            {
                $tmp = array();
                foreach($fields as $f)
                {
                    $tmp[$f] = stripslashes($row[$f]);
                }
                $data[] = $tmp;
            }
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
        
        return $GLOBALS['B']->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
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

        $uid = $GLOBALS['B']->db->nextId($GLOBALS['B']->sys['db']['table_prefix'].'_seq_add_user');

        if (DB::isError($uid)) 
        {
            trigger_error($uid->getMessage(), E_USER_ERROR);
        }
        
        $sql = '
            INSERT INTO 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'user_users
                (uid,forename,lastname,email,login,passwd,status,rights)
            VALUES
                ('.$data['uid'].',
                 '.$data['forename'].',
                 '.$data['lastname'].',
                 '.$data['email'].',
                 '.$data['login'].',
                 '.$data['passwd'].',
                 '.$data['status'].',
                 '.$data['rights'].')';
        
        $res = $GLOBALS['B']->db->query($sql);

        if (DB::isError($res)) 
        {
            trigger_error($res->getMessage(), E_USER_ERROR);
        }
        
        return $uid;
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
        
        return $GLOBALS['B']->db->query($sql);
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
        
        return $GLOBALS['B']->db->query($sql);
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
        
        $result = &$GLOBALS['B']->db->query($sql);
        return $result->numRows();    
    }
    
    /**
     * add_registered_user_data
     *
     * @param int $uid user ID
     * @return mixed md5_str|false
     */     
    function add_registered_user_data( $uid )
    {
        $md5_str = $GLOBALS['B']->util->unique_md5_str();
        
        $sql = '
            INSERT INTO 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'user_registered
                (uid,md5_str)
            VALUES
                ('.$uid.',
                 "'.$md5_str.'")';
        
        $res = $GLOBALS['B']->db->query($sql);

        if (DB::isError($res)) 
        {
            trigger_error($res->getMessage(), E_USER_ERROR);
            return FALSE;
        }
        
        return $md5_str;
    }
    
    /**
     * auto_validate_registered_user
     *
     * @param string $md5_str Md5 string
     */
    function auto_validate_registered_user( $md5_str )
    {
        $this->delete_expired_registered_users();
        
        // validate md5 string
        if(!preg_match("/^[a-f0-9]{32}$/i", $md5_str) > 0)
        {
            return FALSE;
        }
        
        $sql = "
            SELECT
                uid
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}user_registered
            WHERE
                md5_str='{$md5_str}'";
        
        $row = $GLOBALS['B']->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
        
        if(!isset($row['uid']))
        {
            return FALSE;
        }
        
        $sql = '
            UPDATE 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'user_users
            SET
                status=2
            WHERE
                uid='.$row['uid'];
        
        $GLOBALS['B']->db->query($sql);
        
        $sql = "
            DELETE FROM 
                {$GLOBALS['B']->sys['db']['table_prefix']}user_registered
            WHERE
                md5_str='{$md5_str}'";
        
        $GLOBALS['B']->db->query($sql);
    }    

    /**
     * delete_expired_registered_users
     *
     */    
    function delete_expired_registered_users()
    {
        $_date = date('Y-m-d H:i:s', time() - 86400);

        $sql = "
            SELECT
                uid
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}user_registered
            WHERE
                reg_date<'{$md5_str}'";
        
        $result = $GLOBALS['B']->db->query($sql);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage(), E_USER_ERROR);
        }        
        
        if(is_object($result))
        {        
            while($row = &$result->fetchRow( DB_FETCHMODE_ASSOC ))
            {
                $sql = "
                    DELETE FROM 
                        {$GLOBALS['B']->sys['db']['table_prefix']}user_registered
                    WHERE
                        uid={$row['uid']}";
        
                $GLOBALS['B']->db->query($sql);    
                
                $sql = "
                    DELETE FROM 
                        {$GLOBALS['B']->sys['db']['table_prefix']}user_users
                    WHERE
                        uid={$row['uid']}";
        
                $GLOBALS['B']->db->query($sql);                   
            }
        }
    }
}

?>
