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
 * earchive_get_messages class 
 *
 */
 
class earchive_get_messages
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
    function earchive_get_messages()
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
     * Assign array with messages data of a list
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
        
        $comma   = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $order = ''; 
        
        if( !empty($data['order']) )
        {
            $order = "ORDER BY {$data['order']}";
        }

        if( !empty($data['pager']['limit']) )
        {
            $data['pager']['limit'] = 15;
        }        

        if(empty($_GET['pageID']) || ($_GET['pageID']==1))
            $page = 0;
        else
            $page = ($_GET['pageID'] - 1) * $data['pager']['limit'];
        
        $this->B->db->setLimit( (string)$data['pager']['limit'], (string)$page );
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                lid={$data['lid']} 
            {$order}";

            
        $result = $this->B->db->query( $sql );

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }

        // get var name to store the result
        $this->B->$data['var'] = array();
        $_result       = & $this->B->$data['var'];

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
        $this->_pager( $data['lid'], $data['pager'] );
        
        return TRUE;     
    } 
    
    /**
     * build the pager links
     *
     * @param int $lid list id
     * @param int $limit limit of items to show
     * @param string $pager_var Variable name to store pager links
     * @access privat
     */ 
    function _pager( $lid, & $data )
    {
        // PEAR Pager class
        include_once(SF_BASE_DIR.'modules/common/PEAR/Pager/Sliding.php');
        
        $sql = "
            SELECT
                count(lid) AS num_rows
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                lid={$lid}";        

        $result = $this->B->db->query( $sql );
        
        //$_result = $this->B->db->getRow( $sql, NULL, array(), NULL,  DB_FETCHMODE_ASSOC );

        $_result = $result->fetchRow( DB_FETCHMODE_ASSOC );

        $params['totalItems'] = $_result['num_rows'];
        
        if( empty($data['delta']) )
        {
            $data['delta'] = 3;
        }        
        
        $params['perPage']    = $data['limit'];

        $params['delta']      = $data['delta'];
            
        $pager = & new Pager_Sliding($params);
        $links = $pager->getLinks();
        $this->B->$data['var'] = $links['all'];    
    }     
}

?>
