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
     * Assign array with messages data of a list.
     * use cache.
     *
     * @param array $data
     */
    function perform( & $data )
    {
        // get var name to store the result
        $this->_result       = & $this->B->$data['var'];
        
        if(empty($data['lid']) || empty($data['var']))
        {
            trigger_error("'lid' or 'var' is empty!\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }           
        
        $_fields = implode(',', $data['fields'] );
        
        $order = ''; 
        
        if( !empty($data['order']) )
        {
            $order = "ORDER BY {$data['order']}";
        }

        if( !empty($data['pager']['limit']) )
        {
            $data['pager']['limit'] = 15;
        }        

        $_where = '';
        if( $data['mode'] == 'tree' )
        {
            $_where = " AND root_id='' ";
            if( !isset($data['fields']['root_id']) )
            {
                $_fields .= ',message_id ';   
            }
        }

        // build pager links
        $this->_pager( $data['lid'], $data['pager'], $_where );
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                lid={$data['lid']} 
                {$_where}
            {$order}";

        if(empty($_GET['pageID']) || ($_GET['pageID']==1))
            $page = 0;
        else
            $page = ($_GET['pageID'] - 1) * $data['pager']['limit'];

        // check if cache ID exists
        if ( M( MOD_COMMON, 
                'cache_get',
                array('result'     => $data['var'],
                      'cacheID'    => SF_SECTION.$sql.(string)$page,
                      'cacheGroup' => 'earchive'))) 
        {
            return TRUE;
        }

        // init result array
        $this->_result = array();
                        
        $result = $this->B->db->limitQuery( $sql, $page, $data['pager']['limit'] );
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }

        if(is_object($result))
        {
            while($row = $result->FetchRow( DB_FETCHMODE_ASSOC ))
            {
                $tmp = array();
                foreach($data['fields'] as $f)
                {
                    $tmp[$f] = stripslashes($row[$f]);
                }
                
                $tmp['level'] = 0;
                
                $this->_result[] = $tmp;
                
                if( ($data['mode'] == 'tree') && !empty($row['message_id']) )
                {
                    $this->_get_childs( $data, $_fields, $row['message_id'] );
                } 
            }
        }
        
        // save result to cache
        M( MOD_COMMON, 
           'cache_save',
           array('result' => $this->_result));
                     
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
    function _pager( $lid, & $data, &$_where )
    {
        // get var name to store the result
        $_result       = & $this->B->$data['var'];

        // check if cache ID exists
        if (M( MOD_COMMON, 
               'cache_get',
               array('result'     => $data['var'],
                     'cacheID'    => SF_SECTION.$lid.$_GET['mode'].$_GET['pageID'],
                     'cacheGroup' => 'earchive'))) 
        {
            return TRUE;
        }

        $sql = "
            SELECT
                count(lid) AS num_rows
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                lid={$lid} {$_where}";  
        
        $result = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
        
        // PEAR Pager class
        include_once(SF_BASE_DIR.'modules/common/PEAR/Pager/Sliding.php');
       
        $params['totalItems'] = $result['num_rows'];
        
        if( empty($data['delta']) )
        {
            $data['delta'] = 3;
        }        
        
        $params['perPage']    = $data['limit'];
            
        $params['delta']      = $data['delta'];
            
        $pager = & new Pager_Sliding($params);
        $links = $pager->getLinks();
        $_result = $links['all'];  

        // save result to cache
        M( MOD_COMMON, 
           'cache_save',
           array('result' => $links['all']));
    } 
    /**
     * get childrens of a message_id
     *
     * @param array $data
     * @param string $_fields
     * @param string $message_id 
     * @access privat
     */    
    function _get_childs( &$data, &$_fields, $message_id )
    {
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                root_id='{$message_id}' 
            ORDER BY mdate ASC";
            
        $result = $this->B->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }            
        
        while($row = $result->FetchRow( DB_FETCHMODE_ASSOC ))
        {
            $tmp = array();
            foreach($data['fields'] as $f)
            {
                $tmp[$f] = stripslashes($row[$f]);
            }
            $tmp['level'] = 1;   
            $this->_result[] = $tmp;
        }        
    }
}

?>
