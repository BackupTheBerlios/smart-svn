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
 * EARCHIVE_LIST_MESSAGES class 
 *
 */
 
class EARCHIVE_LIST_MESSAGES
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
    function EARCHIVE_LIST_MESSAGES()
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
     * Get all available email lists
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // check if message belongs to a restricted list
        $this->get_list( array('lid' => (int)$data['lid'], 'fields' => array('status')) );
    
        $comma   = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        if(!empty($data['order']))
            $order = 'mdate DESC';
        else
            $order = $data['order'];
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                lid={$data['lid']} 
            ORDER BY {$order}";

        if(empty($_GET['pageID']) || ($_GET['pageID']==1))
            $page = 0;
        else
            $page = ($_GET['pageID'] - 1) * $data['pager']['limit'];
            
        $result = $this->B->db->limitQuery($sql,$page,$data['pager']['limit']);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }

        // get var name to store the result
        $this->B->$data['var'] = array();
        $_result                    = & $this->B->$data['var'];

        if(is_object($result))
        {
            while($row = $result->FetchRow( DB_FETCHMODE_ASSOC ))
            {
                $tmp = array();
                foreach($data['fields'] as $f)
                {
                    $tmp[$f] = stripslashes($row[$f]);
                }
                $_result[] = $tmp;
            }
        }
        $this->_pager( $data );
    }
    
    /**
     * get attachment data
     *
     * @param array $data Col names of the attach db table and instructions
     * @access privat
     */ 
    function _pager( &$data )
    {
        // PEAR Pager class
        include_once(SF_BASE_DIR.'/admin/modules/common/PEAR/Pager/Sliding.php');
        
        $sql = "
            SELECT
                count(lid) AS num_rows
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                lid={$data['lid']}";        

        $_result = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);

        $params['totalItems'] = $_result['num_rows'];
        
        if(!empty($data['pager']['limit']))
            $params['perPage']    = $data['pager']['limit'];
        else
            $params['perPage']    = 10;

        if(!empty($data['pager']['delta']))
            $params['delta']    = $data['pager']['delta'];
        else
            $params['delta']    = 2;
            
        $pager = &new Pager_Sliding($params);
        $links = $pager->getLinks();
        $this->B->$data['pager']['var'] = $links['all'];    
    }    
}

?>
