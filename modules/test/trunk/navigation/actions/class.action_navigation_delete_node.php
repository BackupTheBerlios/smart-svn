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

class action_navigation_delete_node extends action
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
        
        // add node to the array
        $this->B->tree->deleteNode( $data['node'] );

        // Update navigation node config file
        // see modules/common/actions/class.action_common_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $this->B->tree->node,
                  'file'     => SF_BASE_DIR . 'data/navigation/nodes.php',
                  'var_name' => 'node',
                  'type'     => 'PHPArray') );


        // Delete cache data
        M( MOD_COMMON, 'cache_delete', array( 'id'    => 'public'.$data['node'],
                                              'group' => 'navigation'));  
        // Delete cache data
        M( MOD_COMMON, 'cache_delete', array('group' => 'navigation-tree'));  
        
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
        // validate $data['node']. no chars else than 0123456789 and - are accepted
        if( preg_match("/[^0-9-]/", $data['node']) )
        {
            return FALSE;
        }     
        
        return TRUE;
    }     
}

?>