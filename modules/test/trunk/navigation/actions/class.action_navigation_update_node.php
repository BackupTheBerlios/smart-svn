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
// tree class
include_once(SF_BASE_DIR . 'modules/common/includes/Tree.php');

class action_navigation_update_node extends action
{
    /**
     * Update navigation node title and body
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        // check if an tree instance exists
        if(!is_object($this->B->tree))
        {
            // load navigation nodes
            $node = array();
            include_once(SF_BASE_DIR . 'data/navigation/nodes.php');
        
            $this->B->tree = &Tree::createFromArray($node);
        }  

        // load data of the requested node
        $ndata = $this->B->tree->getData( $data['node'] ); 
          
        $this->fp = new File();
        
        // Update navigation node body
        $node_file  = SF_BASE_DIR . 'data/navigation/'.$ndata['node']; 
        if (!is_int($this->fp->write  ( $node_file, commonUtil::stripSlashes($data['body']), FILE_MODE_WRITE, FILE_LOCK_EXCLUSIVE )))
        {
            $this->B->$data['error'] = 'Could not write to file: '.$node_file;
            return FALSE;
        }
        $this->fp->unlock ( $node_file, FILE_MODE_WRITE );
        
        // replace single and double quotes
        $search_array = array('\'','"');
        $replace_array = array('&#039;','&quot;');

        $tmp = array('id'     => $data['node'],
                     'title'  => str_replace ( $search_array, $replace_array, commonUtil::stripSlashes_special($data['title'])),
                     'node'   => $ndata['node'],
                     'status' => $data['status'],
                     'order'  => $ndata['order']);

        $this->B->tree->setData( $data['node'], $tmp ); 

        // Update navigation node title
        // see modules/common/actions/class.action_common_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $this->B->tree->data,
                  'file'     => SF_BASE_DIR . 'data/navigation/nodes.php',
                  'var_name' => 'node',
                  'type'     => 'PHPArray') );

        // Delete cache data
        M( MOD_COMMON, 'cache_delete', array( 'id'    => SF_SECTION.$data['node'],
                                              'group' => 'navigation'));
        M( MOD_COMMON, 'cache_delete', array( 'id'    => SF_SECTION.$data['node'],
                                              'group' => 'navigation_nav'));                                              
        
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
            $this->B->$data['error'] = 'Wrong status format !!!';        
            return FALSE;
        }
        // validate $data['node']. no chars else than 0123456789 and - are accepted
        if( preg_match("/[^0-9]/", $data['node']) )
        {
            $this->B->$data['error'] = 'Wrong node format !!!';        
            return FALSE;
        }     
        
        return TRUE;
    }    
}

?>