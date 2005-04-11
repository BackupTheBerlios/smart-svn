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
 * action_earchive_update_list class 
 *
 */
 
class action_earchive_update_list extends action
{
    /**
     * Delete email list data and attachement folder
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        if(empty($data['lid']))
        {
            trigger_error("'lid' is empty!\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        
        $set = '';
        $comma = '';
        
        foreach($data['data'] as $key => $val)
        {
            $set .= $comma.$key.'='.$val;
            $comma = ',';
        }
        
        $sql = '
            UPDATE 
                '.$this->B->sys['db']['table_prefix'].'earchive_lists
            SET
                '.$set.'
            WHERE
                lid='.$data['lid'];
        
        $result = $this->B->db->query($sql);
        
        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        } 
        
        // Delete cache data
        M( MOD_COMMON, 'cache_delete', array('group' => 'earchive'));        
        
        return TRUE;     
    }    
}

?>
