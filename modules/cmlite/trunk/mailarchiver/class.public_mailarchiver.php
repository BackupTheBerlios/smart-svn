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
        
        $result = $GLOBALS['B']->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nSQL: ".$sql."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        // get var name to store the result
        $GLOBALS['B']->$data['var'] = array();
        $_result                    = & $GLOBALS['B']->$data['var'];
        
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

        $_result = $GLOBALS['B']->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
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

        $_result = $GLOBALS['B']->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
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
            $page = 0;
        else
            $page = ($_GET['pageID'] - 1) * $data['pager']['limit'];
            
        $result = $GLOBALS['B']->db->limitQuery($sql,$page,$data['pager']['limit']);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nSQL: ".$sql."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }

        // get var name to store the result
        $GLOBALS['B']->$data['var'] = array();
        $_result                    = & $GLOBALS['B']->$data['var'];

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
        
        $result = $GLOBALS['B']->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nSQL: ".$sql."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        // get var name to store the result
        $GLOBALS['B']->$data['var'] = array();
        $_result                    = & $GLOBALS['B']->$data['var'];
        
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
    }

    /**
     * get email list data
     *
     * @param int $lid List id
     * @param array $fields Field names of the list db table
     * @return array List data 
     */     
    function get_attach( $data )
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
                aid={$data['aid']} 
            AND
                mid={$data['mid']}                
            AND
                lid={$data['lid']}";

        // get var name to store the result
        $GLOBALS['B']->$data['var'] = array();
        $_result                    = & $GLOBALS['B']->$data['var'];

        $_result = $GLOBALS['B']->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
    } 

    function _pager( &$data )
    {
        include_once('Pager_Sliding/Sliding.php');
        
        $sql = "
            SELECT
                count(lid) AS num_rows
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}mailarchiver_messages
            WHERE
                lid={$data['lid']}";        

        $_result = $GLOBALS['B']->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);

        $params['totalItems'] = $_result['num_rows'];
        $params['perPage']    = $data['pager']['limit'];
        $pager = &new Pager_Sliding($params);
        $links = $pager->getLinks();
        $GLOBALS['B']->$data['pager']['var'] = $links['all'];    
    }
}

?>
