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
 * action_user_get class 
 *
 */
 
class action_user_get extends action
{
    /**
     * get user data
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( & $data )
    {
        $this->B->$data['error'] = FALSE;
        $error                   = & $this->B->$data['error'];

        $this->B->$data['result'] = FALSE;
        $result                   = & $this->B->$data['result'];

        $comma = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->B->sys['db']['table_prefix']}user_users
            WHERE
                uid={$data['user_id']}";
        
        $result = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $error = 'Unexpected error';
            return $result = FALSE;
        } 
        
        return TRUE;
    } 
    
    /**
     * validate user id request
     *
     * @param array $data User data
     * @return bool 
     */     
    function validate( & $data )
    {
        // CHECK SECTION
        $admin = '';
        if( SF_SECTION == 'admin')
        {
            $admin = '?admin=1';
        }  
        
        if( $this->_uid_exists( $data['user_id'] ) != 1 )
        {
            trigger_error("No such user ID:".$data['user_id']."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_WARNING);
            @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.$admin);
            exit;            
        }   

        
        
        return TRUE;
    }
    
    /**
     * check if uid exist
     *
     * @param int $uid User id
     * @return int Number of uid's
     */    
    function _uid_exists( $uid )
    {
        $sql = '
            SELECT
                uid
            FROM
                '.$this->B->sys['db']['table_prefix'].'user_users
            WHERE
                uid='.(int)$uid;
        
        $result = $this->B->db->query($sql);

        return (int)$result->numRows();    
    }     
}

?>
