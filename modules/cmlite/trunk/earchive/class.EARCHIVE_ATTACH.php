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
 * EARCHIVE_ATTACH class 
 *
 */
// earchive common class
include_once(SF_BASE_DIR.'/admin/modules/earchive/class.common.php');
 
class EARCHIVE_ATTACH extends earchive_common
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
    function EARCHIVE_ATTACH()
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
     * Get a message from an email list
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // check if message belongs to a restricted list
        $this->list_auth( (int)$data['lid'] );
    
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
