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
     * @return array Lists data 
     */ 
    function get_lists( $fields )
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
                {$GLOBALS['B']->sys['db']['table_prefix']}mailarchiver_lists
            ORDER BY
                name ASC";
        
        $result = $GLOBALS['B']->conn->Execute($sql);
        
        $data = array();
        
        if(is_object($result))
        {
            while($row = $result->FetchRow())
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
                {$GLOBALS['B']->sys['db']['table_prefix']}mailarchiver_lists
            WHERE
                lid={$lid}";
        
        return $GLOBALS['B']->conn->getRow($sql);
    } 
    
    /**
     * add email list
     *
     * @param array $data associative array of list data
     * @return bool true or false
     */     
    function add_list( $data )
    {
        $sql = '
            INSERT INTO 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'mailarchiver_lists
                (name,email,emailserver,description,folder,status)
            VALUES
                ('.$data['name'].',
                 '.$data['email'].',
                 '.$data['emailserver'].',
                 '.$data['description'].',
                 '.$data['folder'].',
                 '.$data['status'].')';

        return $GLOBALS['B']->conn->Execute($sql);
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
                '.$GLOBALS['B']->sys['db']['table_prefix'].'mailarchiver_lists
            SET
                '.$set.'
            WHERE
                lid='.$lid;
        
        return $GLOBALS['B']->conn->Execute($sql);
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
        $path = SF_BASE_DIR.'/data/mailarchiver/'.$data['folder'];
        
        if(!empty($data['folder']) && @is_dir($path))
        {   
            // delete attachements folder for this list
            $GLOBALS['B']->util->delete_dir_tree( $path );
        }
        
        // delete list
        $sql = "
            DELETE FROM 
                {$GLOBALS['B']->sys['db']['table_prefix']}mailarchiver_lists
            WHERE
                lid={$lid}";
        
        $GLOBALS['B']->conn->Execute($sql);
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$GLOBALS['B']->sys['db']['table_prefix']}mailarchiver_messages
            WHERE
                lid={$lid}";
        
        $GLOBALS['B']->conn->Execute($sql);    
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$GLOBALS['B']->sys['db']['table_prefix']}mailarchiver_attach
            WHERE
                lid={$lid}";
        
        $GLOBALS['B']->conn->Execute($sql);          
    }
    /**
     * add email message
     *
     * @param array $data associative array of list data
     * @return bool true or false
     */     
    function add_message( &$data )
    {
        $mid = $GLOBALS['B']->conn->GenID('sequence_add_message');
        
        $sql = '
            INSERT INTO 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'mailarchiver_messages
                (mid,lid,mes_id,sender,subject,mdate,body,folder)
            VALUES
                ('.$mid.',
                 '.$data['lid'].',
                 '.$data['mes_id'].',
                 '.$data['sender'].',
                 '.$data['subject'].',
                 '.$data['mdate'].',
                 '.$data['body'].',
                 '.$data['folder'].')';

        if(FALSE === $GLOBALS['B']->conn->Execute($sql))
            return FALSE;
        else
            return $mid;
    }     
    /**
     * add email message
     *
     * @param array $data associative array of list data
     * @return bool true or false
     */     
    function add_attach( $mid, $lid, &$data )
    {
        $aid = $GLOBALS['B']->conn->GenID('sequence_add_attach');
        
        $sql = '
            INSERT INTO 
                '.$GLOBALS['B']->sys['db']['table_prefix'].'mailarchiver_attach
                (aid,mid,lid,file,size,type)
            VALUES
                ('.$aid.',
                 '.$mid.',
                 '.$lid.',
                 '.$data['file'].',
                 '.$data['size'].',
                 '.$data['type'].')';

        return $GLOBALS['B']->conn->Execute($sql);
    }         
}

?>
