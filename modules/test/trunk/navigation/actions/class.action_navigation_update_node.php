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
        // check if a tree object exists
        if(!is_object($this->B->tree))
        {
            // load navigation nodes
            $file = SF_BASE_DIR . 'data/navigation/nodes.php';     
            
            $this->B->tree = & new Tree($file);
        }      

        // load data of the requested node
        $this->B->tree->updateNode( $data ); 

        // Update navigation node title
        // see modules/common/actions/class.action_common_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $this->B->tree->node,
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
        if( !is_int($data['status']) || ($data['status'] < 0) || ($data['status'] > 2) )
        {
            $this->B->$data['error'] = 'Wrong status format !!!';        
            return FALSE;
        }
        // validate $data['node']. no chars else than 0123456789 and - are accepted
        if( preg_match("/[^0-9-]/", $data['node']) )
        {
            $this->B->$data['error'] = 'Wrong node format !!!';        
            return FALSE;
        }     
        
        return TRUE;
    }    
}

?>