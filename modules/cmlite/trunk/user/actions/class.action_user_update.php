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
 * action_user_update class 
 *
 */
 
class action_user_update extends action
{
    /**
     * update user data
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data )
    {
        $this->B->$data['error'] = FALSE;
        $error                   = & $this->B->$data['error'];

        $set = '';
        $comma = '';
        
        foreach($data['fields'] as $key => $val)
        {
            $set .= $comma.$key.'='.$this->B->db->quoteSmart( commonUtil::addSlashes($val ) );
            $comma = ',';
        }
        
        $sql = '
            UPDATE 
                '.$this->B->sys['db']['table_prefix'].'user_users
            SET
                '.$set.'
            WHERE
                uid='.$data['user_id'];
        
        $result = $this->B->db->query($sql);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $error = 'Unexpected error';
            return FALSE;
        } 
        
        return TRUE;
    } 
}

?>