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
 * action_earchive_get_lists class 
 *
 */
 
class action_earchive_get_lists extends action
{
    /**
     * Assign template array with lists data
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        if( empty($data['var']) )
        {
            trigger_error("'var' is empty!\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }

        if( isset($data['status']) )
        {
            $status = $data['status'];
        }
        elseif( SF_SECTION == 'public' )
        {
            $status = 'status>1';
        }
        else
        {
            $status = 'status>0';
        }
        
        $comma   = '';
        $_fields = '';
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
                {$status}
            ORDER BY
                name ASC";
        
        $result = $this->B->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        
        // get var name to store the result
        $this->B->$data['var'] = array();
        $_result               = & $this->B->$data['var'];
        
        if(is_object($result))
        {
            while($row = &$result->FetchRow( DB_FETCHMODE_ASSOC ))
            {
                $tmp = array();
                foreach($data['fields'] as $f)
                {
                    $tmp[$f] = stripslashes($row[$f]);
                }
                $_result[] = $tmp;
            }
        }
        return TRUE;     
    }    
}

?>
