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
 * The mailarchiver class 
 *
 */
 
class mailarchiver
{
    /**
     * get all lists
     *
     * @param array $fields Field names of the list db table
     */ 
    function get_lists( $data )
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
                {$GLOBALS['B']->sys['db']['table_prefix']}mailarchiver_lists
            WHERE
                status>1
            ORDER BY
                name ASC";
        
        $result = $GLOBALS['B']->conn->Execute($sql);
        
        // get var name to store the result
        $GLOBALS['B']->$data['var'] = array();
        $_result                    = & $GLOBALS['B']->$data['var'];
        
        if(is_object($result))
        {
            while($row = $result->FetchRow())
            {
                $tmp = array();
                foreach($data['fields'] as $f)
                {
                    $tmp[$f] = stripslashes($row[$f]);
                }
                $_result[] = $tmp;
            }
        }
    }

    /**
     * get email list data
     *
     * @param int $lid List id
     * @param array $fields Field names of the list db table
     * @return array List data 
     */     
    function get_list( $data )
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
                {$GLOBALS['B']->sys['db']['table_prefix']}mailarchiver_lists
            WHERE
                status>1                
            AND
                lid={$data['lid']}";

        // get var name to store the result
        $GLOBALS['B']->$data['var'] = array();
        $_result                    = & $GLOBALS['B']->$data['var'];

        $_result = $GLOBALS['B']->conn->getRow($sql);
    } 

    /**
     * get email list data
     *
     * @param int $lid List id
     * @param array $fields Field names of the list db table
     * @return array List data 
     */     
    function get_message( $data )
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
                {$GLOBALS['B']->sys['db']['table_prefix']}mailarchiver_messages
            WHERE
                mid={$data['mid']}                
            AND
                lid={$data['lid']}";

        // get var name to store the result
        $GLOBALS['B']->$data['var'] = array();
        $_result                    = & $GLOBALS['B']->$data['var'];

        $_result = $GLOBALS['B']->conn->getRow($sql);
    } 

    /**
     * get email list data
     *
     * @param int $lid List id
     * @param array $fields Field names of the list db table
     * @return array List data 
     */     
    function get_messages( $data )
    {
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
                {$GLOBALS['B']->sys['db']['table_prefix']}mailarchiver_messages
            WHERE
                lid={$data['lid']} 
            ORDER BY {$order}";

        if(!isset($_GET['pageID']) || ($_GET['pageID']==1))
            $page = -1;
        else
            $page = ($_GET['pageID']-1)*$data['pager']['limit'];
            
        $result = $GLOBALS['B']->conn->SelectLimit($sql,$data['pager']['limit'],$page);

        // get var name to store the result
        $GLOBALS['B']->$data['var'] = array();
        $_result                    = & $GLOBALS['B']->$data['var'];

        if(is_object($result))
        {
            while($row = $result->FetchRow())
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
     * get email list data
     *
     * @param int $lid List id
     * @param array $fields Field names of the list db table
     * @return array List data 
     */     
    function get_message_attach( $data )
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
                {$GLOBALS['B']->sys['db']['table_prefix']}mailarchiver_attach 
            WHERE 
                mid={$data['mid']}
            ORDER BY
                file ASC";
        
        $result = $GLOBALS['B']->conn->Execute($sql);
        
        // get var name to store the result
        $GLOBALS['B']->$data['var'] = array();
        $_result                    = & $GLOBALS['B']->$data['var'];
        
        if(is_object($result))
        {
            while($row = $result->FetchRow())
            {
                $tmp = array();
                foreach($data['fields'] as $f)
                {
                    $tmp[$f] = stripslashes($row[$f]);
                }
                $_result[] = $tmp;
            }
        }
    }

    function _pager( &$data )
    {
        include_once(SF_BASE_DIR.'/admin/lib/Pager_Sliding/Sliding.php');
        
        $sql = "
            SELECT
                count(lid) AS num_rows
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}mailarchiver_messages
            WHERE
                lid={$data['lid']}";        

        $_result = $GLOBALS['B']->conn->getRow($sql);

        $params['totalItems'] = $_result['num_rows'];
        $params['perPage']    = $data['pager']['limit'];
        $pager = &new Pager_Sliding($params);
        $links = $pager->getLinks();
        $GLOBALS['B']->$data['pager']['var'] = $links['all'];    
    }
}

?>
