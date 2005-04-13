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
 * action_earchive_update_message class 
 *
 */
 
class action_earchive_update_message extends action
{
    /**
     * update message data
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        if(empty($data['mid']))
        {
            trigger_error("'mid' is empty!\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
       
        return $this->_update_message( $data );     
    }  

    /**
     * update message data
     *
     * @param array $data
     */
    function _update_message( & $data )
    {
        $set = '';
        $comma = '';
        
        foreach($data['fields'] as $key => $val)
        {
            $set .= $comma.$key.'='.$this->B->db->quoteSmart($val);
            $comma = ',';
        }
        
        $sql = '
            UPDATE 
                '.$this->B->sys['db']['table_prefix'].'earchive_messages
            SET
                '.$set.'
            WHERE
                mid='.$data['mid'];
        
        $result = $this->B->db->query($sql);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }     

        // Delete cache data
        M( MOD_COMMON, 'cache_delete', array('group' => 'earchive'));
        
        return TRUE;
    }
}

?>
