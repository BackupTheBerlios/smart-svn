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
 * user_validate class 
 *
 */
 
class user_validate
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
    function user_validate()
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
     * Validate new user
     *
     * @param array $data
     */
    function perform( $data )
    {
        $this->_delete_expired_registered_users();
        
        // validate md5 string
        if(preg_match("/^[a-f0-9]{32}$/i", $data['md5_str']) == 0)
        {
            return FALSE;
        }
        
        $sql = "
            SELECT
                uid
            FROM
                {$this->B->sys['db']['table_prefix']}user_registered
            WHERE
                md5_str='{$data['md5_str']}'";
        
        $result = $this->B->db->query($sql);

        if (MDB2::isError($result)) 
        {
            trigger_error($res->getMessage()."\n".$res->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }        
        
        $row = $result->fetchRow( MDB2_FETCHMODE_ASSOC );
        
        if(!isset($row['uid']))
        {
            return FALSE;
        }
        
        $sql = '
            UPDATE 
                '.$this->B->sys['db']['table_prefix'].'user_users
            SET
                status=2
            WHERE
                uid='.$row['uid'];
        
        $result = $this->B->db->query($sql);

        if (MDB2::isError($result)) 
        {
            trigger_error($res->getMessage()."\n".$res->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        } 
        
        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}user_registered
            WHERE
                md5_str='{$data['md5_str']}'";
        
        $result = $this->B->db->query($sql);

        if (MDB2::isError($result)) 
        {
            trigger_error($res->getMessage()."\n".$res->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        } 
        
        return TRUE;
    } 
    /**
     * delete_expired_registered_users
     *
     */    
    function _delete_expired_registered_users()
    {
        $_date = date('Y-m-d H:i:s', time() - 86400);

        $sql = "
            SELECT
                uid
            FROM
                {$this->B->sys['db']['table_prefix']}user_registered
            WHERE
                reg_date<'{$_date}'";
        
        $result = $this->B->db->query($sql);
        
        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }        
        
        if(is_object($result))
        {        
            while($row = &$result->fetchRow( MDB2_FETCHMODE_ASSOC ))
            {
                $sql = "
                    DELETE FROM 
                        {$this->B->sys['db']['table_prefix']}user_registered
                    WHERE
                        uid={$row['uid']}";
        
                $result = $this->B->db->query($sql);

                if (MDB2::isError($result)) 
                {
                    trigger_error($res->getMessage()."\n".$res->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                }    
                
                $sql = "
                    DELETE FROM 
                        {$this->B->sys['db']['table_prefix']}user_users
                    WHERE
                        uid={$row['uid']}";
        
                $result = $this->B->db->query($sql);
        
                if (MDB2::isError($result)) 
                {
                    trigger_error($res->getMessage()."\n".$res->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                }                
            }
        }
    }    
}

?>
