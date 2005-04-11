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
 * action_earchive_get_attachments class 
 *
 */
 
class action_earchive_get_attachments extends action
{
    /**
     * Assign array with message attachments data
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        if(empty($data['mid']) || empty($data['var']))
        {
            trigger_error("'mid' or 'var' is empty!\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        
        // get var name to store the result
        $this->B->$data['var'] = array();
        $_result               = & $this->B->$data['var'];              
        
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
                {$this->B->sys['db']['table_prefix']}earchive_attach
            WHERE
                mid={$data['mid']}            
            ORDER BY
                file ASC";

        $result = $this->B->db->query($sql);

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        $_result = array();
        
        if(is_object($result))
        {
            while($row = $result->fetchRow( MDB2_FETCHMODE_ASSOC ))
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
