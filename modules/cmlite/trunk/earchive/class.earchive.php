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
 * The earchive class 
 *
 */
 
class earchive
{
    /**
     * get all lists
     *
     * @param array $fields Field names of the list db table
     * @return array Lists data 
     */ 
    function get_lists( $fields, $status = FALSE )
    {
        if(FALSE !== $status)
            $where = 'WHERE '.$status;
        else
            $where = '';
        
        $comma = '';
        foreach ($fields as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_lists 
                {$where} 
            ORDER BY
                name ASC";
        
        $result = $GLOBALS['B']->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        $data = array();
        
        if(is_object($result))
        {
            while($row = &$result->FetchRow( DB_FETCHMODE_ASSOC ))
            {
                $tmp = array();
                foreach($fields as $f)
                {
                    $tmp[$f] = stripslashes($row[$f]);
                }
                $data[] = $tmp;
            }
        }
        return $data;
    }

    /**
     * get email list data
     *
     * @param int $lid List id
     * @param array $fields Field names of the list db table
     * @return array List data 
     */     
    function get_list( $lid, $fields )
    {
        $comma = '';
        foreach ($fields as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_lists
            WHERE
                lid={$lid}";
        
        return $GLOBALS['B']->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
    } 
    
    /**
     * add email list
     *
     * @param array $data associative array of list data
     * @return bool true or false
     */     
    function add_list( $data )
    {
        $lid = $GLOBALS['B']->db->nextId($GLOBALS['B']->sys['db']['table_prefix'].'earchive_seq_add_list');

        if (DB::isError($lid)) 
        {
            trigger_error($lid->getMessage(), E_USER_ERROR);
        }
        
        $sql = '
            INSERT INTO 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'earchive_lists
                (lid,name,email,emailserver,description,folder,status)
            VALUES
                ('.$lid.',
                 '.$data['name'].',
                 '.$data['email'].',
                 '.$data['emailserver'].',
                 '.$data['description'].',
                 '.$data['folder'].',
                 '.$data['status'].')';

        $result = $GLOBALS['B']->db->query($sql);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }   
        
        return $result;
    } 
    /**
     * update email list data
     *
     * @param int $lid List id
     * @param array $data associative array of list data
     */
    function update_list( $lid, $data )
    {
        $set = '';
        $comma = '';
        
        foreach($data as $key => $val)
        {
            $set .= $comma.$key.'='.$val;
            $comma = ',';
        }
        
        $sql = '
            UPDATE 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'earchive_lists
            SET
                '.$set.'
            WHERE
                lid='.$lid;
        
        $result = $GLOBALS['B']->db->query($sql);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }   
        
        return $result;        
    } 

    /**
     * delete email list archiv
     *
     * @param int $lid List id
     */
    function delete_list( $lid )
    {
        // get attachments folder
        $fields = array('folder');
        $data = $this->get_list( $lid, $fields );
        $path = SF_BASE_DIR.'/data/earchive/'.$data['folder'];
        
        if(!empty($data['folder']) && @is_dir($path))
        {   
            // delete attachements folder for this list
            $GLOBALS['B']->util->delete_dir_tree( $path );
        }
        
        // delete list
        $sql = "
            DELETE FROM 
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_lists
            WHERE
                lid={$lid}";
        
        $GLOBALS['B']->db->query($sql);
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_messages
            WHERE
                lid={$lid}";
        
        $GLOBALS['B']->db->query($sql);    
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_attach
            WHERE
                lid={$lid}";
        
        $GLOBALS['B']->db->query($sql); 
        
        // delete list messages word indexes
        $sql = "
            DELETE FROM 
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_words_crc32
            WHERE
                lid={$lid}";
        
        $GLOBALS['B']->db->query($sql);        
    }
    /**
     * add email message
     *
     * @param array $data associative array of list data
     * @return mixed message ID or false
     */     
    function add_message( &$data )
    {
        $mid = $GLOBALS['B']->db->nextId($GLOBALS['B']->sys['db']['table_prefix'].'earchive_seq_add_message');

        if (DB::isError($mid)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        $sql = '
            INSERT INTO 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'earchive_messages
                (mid,lid,sender,subject,mdate,body,folder)
            VALUES
                ('.$mid.',
                 '.$data['lid'].',
                 '.$data['sender'].',
                 '.$data['subject'].',
                 '.$data['mdate'].',
                 '.$data['body'].',
                 '.$data['folder'].')';

        $result = $GLOBALS['B']->db->query($sql);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }   
        
        return $mid;
    }     
    /**
     * add email message attachment
     *
     * @param int $mid Message ID
     * @param int $lid List ID
     * @param array $data associative array of list data
     * @return bool true or false
     */     
    function add_attach( $mid, $lid, &$data )
    {
        $aid = $GLOBALS['B']->db->nextId($GLOBALS['B']->sys['db']['table_prefix'].'earchive_seq_add_attach');

        if (DB::isError($aid)) 
        {
            trigger_error($result->getMessage(), E_USER_ERROR);
        }
        
        $sql = '
            INSERT INTO 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'earchive_attach
                (aid,mid,lid,file,size,type)
            VALUES
                ('.$aid.',
                 '.$mid.',
                 '.$lid.',
                 '.$data['file'].',
                 '.$data['size'].',
                 '.$data['type'].')';

        $result = $GLOBALS['B']->db->query($sql);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }   
        
        return $result;
    }  
    /**
     * get all messages
     *
     * @param array $fields Field names of the list db table
     * @return array Lists data 
     */ 
    function & get_all_messages( $fields )
    {
        $comma = '';
        foreach ($fields as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_messages";
        
        $result = $GLOBALS['B']->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }

        return $result;
    }
    
    /**
     * get all list messages data
     * (support pagination)
     *
     * @param array $data Col names of the message db table and instructions
     */     
    function get_messages( $var, $lid, $fields, $pager_var, $limit = 20 )
    {
        $comma   = '';
        $_fields = '';
        foreach ($fields as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_messages
            WHERE
                lid={$lid} 
            ORDER BY mdate DESC";

        if(empty($_GET['pageID']) || ($_GET['pageID']==1))
            $page = 0;
        else
            $page = ($_GET['pageID'] - 1) * $limit;
            
        $result = $GLOBALS['B']->db->limitQuery($sql,$page,$limit);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }

        // get var name to store the result
        $GLOBALS['B']->$var = array();
        $_result            = & $GLOBALS['B']->$var;

        if(is_object($result))
        {
            while($row = $result->FetchRow( DB_FETCHMODE_ASSOC ))
            {
                $tmp = array();
                foreach($fields as $f)
                {
                    $tmp[$f] = stripslashes($row[$f]);
                }
                $_result[] = $tmp;
            }
        }
        $this->_pager( $lid, $limit, $pager_var );
    } 
    
    /**
     * delete message and all related data
     *
     * @param int $mid message id
     */
    function delete_message( $mid )
    {
        // get attachments folder
        $fields = array('lid','folder');
        $data = $this->get_message( $mid, $fields );
        $l_data = $this->get_list( $data['lid'], $fields );        
        
        $path = SF_BASE_DIR.'/data/earchive/'.$l_data['folder'].'/'.$data['folder'];
        
        if(!empty($data['folder']) && @is_dir($path))
        {   
            // delete attachements folder for this list
            $GLOBALS['B']->util->delete_dir_tree( $path );
        }
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_messages
            WHERE
                mid={$mid}";
        
        $GLOBALS['B']->db->query($sql);    
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_attach
            WHERE
                mid={$mid}";
        
        $GLOBALS['B']->db->query($sql); 
        
        // delete list messages word indexes
        $sql = "
            DELETE FROM 
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_words_crc32
            WHERE
                mid={$mid}";
        
        $GLOBALS['B']->db->query($sql);     
    }
    /**
     * get message data
     *
     * @param int $mid message id
     * @param array $fields Name of fields to fetch
     * @return array Message data 
     */     
    function get_message( $mid, $fields )
    {        
        $comma   = '';
        $_fields = '';
        foreach ($fields as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_messages
            WHERE
                mid={$mid}";

        return $GLOBALS['B']->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
    }     
    /**
     * build the pager links
     *
     * @param int $lid list id
     * @param int $limit limit of items to show
     * @param string $pager_var Variable name to store pager links
     * @access privat
     */ 
    function _pager( $lid, $limit = 20, $pager_var )
    {
        // PEAR Pager class
        include_once(SF_BASE_DIR.'/admin/modules/user/PEAR/Pager/Sliding.php');
        
        $sql = "
            SELECT
                count(lid) AS num_rows
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_messages
            WHERE
                lid={$lid}";        

        $_result = $GLOBALS['B']->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);

        $params['totalItems'] = $_result['num_rows'];
        
        $params['perPage']    = $limit;

        $params['delta']      = 2;
            
        $pager = &new Pager_Sliding($params);
        $links = $pager->getLinks();
        $GLOBALS['B']->$pager_var = $links['all'];    
    }  

    /**
     * get message attachments
     *
     * @param int $mid Message ID
     * @param array $fields Fields to fetch
     * @return array
     */     
    function get_message_attach( $mid, $fields )
    {
        $comma   = '';
        $_fields = '';
        foreach ($fields as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_attach 
            WHERE 
                mid={$mid}
            ORDER BY
                file ASC";
        
        $result = $GLOBALS['B']->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        $_result = array();
        
        if(is_object($result))
        {
            while($row = $result->FetchRow( DB_FETCHMODE_ASSOC ))
            {
                $tmp = array();
                foreach($fields as $f)
                {
                    $tmp[$f] = stripslashes($row[$f]);
                }
                $_result[] = $tmp;
            }
        }
        return $_result;
    }

    /**
     * get attachment
     *
     * @param int $aid Attach ID
     * @param array $fields Fields to fetch
     * @return array
     */     
    function get_attach( $aid, $fields )
    {
        $comma   = '';
        $_fields = '';
        foreach ($fields as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_attach 
            WHERE 
                aid={$aid}";
        
        $result = $GLOBALS['B']->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        } 
        
        $tmp = array();
        
        if(is_object($result))
        {
            $row = $result->FetchRow( DB_FETCHMODE_ASSOC );
            
            foreach($fields as $f)
            {
                $tmp[$f] = stripslashes($row[$f]);
            }
        }
        return $tmp;
    }

    /**
     * delete attachment db entry
     *
     * @param int $aid Attach ID
     */ 
    function delete_attach_db_entry( $aid )
    {
        if(empty($aid))
          return;
          
        $sql = "
            DELETE FROM 
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_attach
            WHERE
                aid={$aid}";  
        
        $result = $GLOBALS['B']->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }                 
    }
    
    /**
     * update email list data
     *
     * @param int $lid List id
     * @param array $data associative array of list data
     */
    function update_message( $mid, $data )
    {
        $set = '';
        $comma = '';
        
        foreach($data as $key => $val)
        {
            $set .= $comma.$key.'='.$val;
            $comma = ',';
        }
        
        $sql = '
            UPDATE 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'earchive_messages
            SET
                '.$set.'
            WHERE
                mid='.$mid;
        
        $result = $GLOBALS['B']->db->query($sql);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }   
        
        return $result;        
    }     
}
?>
