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
 * The earchive class provide methods to use by
 * the public event handler of this module "earchive".
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
 
class earchive
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
                {$this->B->sys['db']['table_prefix']}earchive_lists
            WHERE
                status>1
            ORDER BY
                name ASC";
        
        $result = $this->B->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        // get var name to store the result
        $this->B->$data['var'] = array();
        $_result                    = & $this->B->$data['var'];
        
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
     * get message data
     *
     * @param array $data Col names of the message db table and instructions
     * @return array Message data 
     */     
    function get_message( $data )
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
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                mid={$data['mid']}                
            AND
                lid={$data['lid']}";

        // get var name to store the result
        $this->B->$data['var'] = array();
        $_result                    = & $this->B->$data['var'];

        $_result = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
    } 

    /**
     * get all list messages data
     * (support pagination)
     *
     * @param array $data Col names of the message db table and instructions
     */     
    function get_messages( $data )
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
     * get message attachments
     *
     * @param array $data Col names of the attach db table and instructions
     */     
    function get_message_attach( $data )
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
    }

    /**
     * get attachment data
     *
     * @param array $data Col names of the attach db table and instructions
     */     
    function get_attach( $data )
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
        $_result                    = & $this->B->$data['var'];

        $_result = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
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
