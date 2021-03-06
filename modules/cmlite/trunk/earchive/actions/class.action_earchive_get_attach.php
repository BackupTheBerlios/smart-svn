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
 * action_earchive_get_attach class 
 *
 */
 
class action_earchive_get_attach extends action
{
    /**
     * Get a message attachment 
     *
     * @param array $data
     */
    function perform( $data )
    {        
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
                {$this->B->sys['db']['table_prefix']}earchive_attach
            WHERE
                aid={$data['aid']} 
            AND
                mid={$data['mid']}                
            AND
                lid={$data['lid']}";

        // get var name to store the result
        $this->B->$data['var'] = array();
        $_result               = & $this->B->$data['var'];

        $_result = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
    }
}

?>
