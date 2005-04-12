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
 * action_user_delete class 
 *
 */
 
class action_user_delete extends action
{
    /**
     * delete user
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data )
    {
        $this->B->$data['error'] = FALSE;
        $error                   = & $this->B->$data['error'];

        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}user_users
            WHERE
                uid={$data['user_id']}";
        
        $result = $this->B->db->query($sql);
        
        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->getCode()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $error = 'Unexpected error';
            return FALSE;
        } 
        
        return TRUE;
    } 
}

?>
