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
 * action_navigation_update_node class 
 *
 */
 
// PEAR File
include_once('File.php');

class action_navigation_update_node extends action
{
    /**
     * Update navigation node title and body
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $this->fp = new File();
        
        // Update navigation node body
        $node  = SF_BASE_DIR . 'data/navigation/'.$data['node']; 
        if (!is_int($this->fp->write  ( $node, commonUtil::stripSlashes($data['body']), FILE_MODE_WRITE, FILE_LOCK_EXCLUSIVE )))
        {
            $this->B->$data['error'] = 'Could not write to file: '.$node;
            return FALSE;
        }
        $this->fp->unlock ( $node, FILE_MODE_WRITE );
        
        // load navigation node titles
        $nav = array();
        include(SF_BASE_DIR . 'data/navigation/nodes.php');
        
        // init loop var
        $x = 0;
         //str_replace ( "'", "&#039;",commonUtil::stripSlashes($data['title']))
        // Look at the node id and assign the new title
        foreach($nav as $node)
        {
            list($id, $val) = each($node);
            if($data['node'] == $id)
            {
                $nav[$x][$id]= array('title'  => str_replace ( "'", "&#039;",commonUtil::stripSlashes_special($data['title'])),
                                     'status' => $data['status']);
                break;
            }
            $x++;
        } 
        
        // Update navigation node title
        // see modules/common/actions/class.action_common_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $nav,
                  'file'     => SF_BASE_DIR . 'data/navigation/nodes.php',
                  'var_name' => 'nav',
                  'type'     => 'PHPArray') );
        
        return TRUE;
    }   
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    function validate(  $data = FALSE  )
    {
        // validate $data['title']. Must contains data
        if( empty( $data['title'] ) )
        {   
            $this->B->$data['error'] = 'Title field is empty!!!';
            return FALSE;
        }        
        // validate $data['status']. it should be "drawt" or "publish"
        if( !preg_match("/drawt|publish/", $data['status']) )
        {
            $this->B->$data['error'] = 'Hey, dont hack !!!';        
            return FALSE;
        }
        // validate $data['node']. no chars else than 0123456789 and - are accepted
        if( preg_match("/[^0-9-]/", $data['node']) )
        {
            $this->B->$data['error'] = 'Hey, dont hack !!!';        
            return FALSE;
        }     
        
        return TRUE;
    }    
}

?>
