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
 * EARCHIVE_LIST class 
 *
 */
 
class EARCHIVE_LIST
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
    function EARCHIVE_LIST()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $this->B;
    }
    
    /**
     * Get all available email lists
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

        // status field required
        if(!strstr($_fields, 'status'))
        {
            $_fields .= ',status';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_lists
            WHERE
                status>1                
            AND
                lid={$data['lid']}";

        // get var name to store the result
        $this->B->$data['var'] = array();
        $_result                    = & $this->B->$data['var'];

        $_result = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
        
        // check if authentication required
        if($_result['status'] == 3)
        {
            $this->_auth();
        }
    }
    /**
     * check if user is registered
     *
     * @access privat
     */     
    function _auth()
    {
        if( $this->B->auth->is_user !== FALSE )
        {
            return TRUE;
        }
        else
        {
            $query = base64_encode(commonUtil::getQueryString());
            @header('Location: '.SF_BASE_LOCATION.'/index.php?tpl=login&ret='.$query);
            exit;
        }
    }    
}

?>
