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
 * action_earchive_get_list class 
 *
 */
 
class action_earchive_get_list extends action
{
    /**
     * Assign template array with list data
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        if(empty($data['lid']) || empty($data['var']))
        {
            trigger_error("'lid' or 'var' is empty!\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        
        // get var name to store the result
        $this->B->$data['var'] = array();
        $result                = & $this->B->$data['var'];              
        
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
                {$this->B->sys['db']['table_prefix']}earchive_lists
            WHERE
                lid={$data['lid']}";
        
        $result = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }

        return TRUE;     
    }    
}

?>
