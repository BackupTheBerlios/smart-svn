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
 * The mailarchiver class provide methods to use by
 * the public event handler of this module "mailarchiver".
 *
 * Each method is calling with a $data param which is an array
 * that contains variables that the method needs to provide
 * the demanded result.
 * 
 * $data['fields'] - names of the db table cols to fetch
 * $data['var'] - The variable name where to store the result.
 *                The result will be available in templates
 *                $B->$data['var']
 * $data['lid'] - List ID
 * $data['mid'] - Message ID
 * $data['aid'] - Attachment ID
 * $data['pager']['var'] - Var name of the pager links
 * $data['pager']['limit'] - Limit of the results
 *
 */
 
class mailarchiver
{
    /**
     * get all email lists
     *
     * @param array $data Col names of the lists db table and instructions
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
     * @param array $data Col names of the list db table and instructions
     * @return array Email list data 
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
     * get message data
     *
     * @param array $data Col names of the message db table and instructions
     * @return array Message data 
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
     * get all list messages data
     * (support pagination)
     *
     * @param array $data Col names of the message db table and instructions
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

        if(empty($_GET['pageID']) || ($_GET['pageID']==1))
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
     * get message attachments
     *
     * @param array $data Col names of the attach db table and instructions
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
     * get attachment data
     *
     * @param array $data Col names of the attach db table and instructions
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

    /**
     * get attachment data
     *
     * @param array $data Col names of the attach db table and instructions
     * @access privat
     */ 
    function _pager( &$data )
    {
        // PEAR Pager class
        include_once('Pager/Sliding.php');
        
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
