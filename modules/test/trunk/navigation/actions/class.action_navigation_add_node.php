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
 * action_navigation_add_node class 
 *
 */

// PEAR File
include_once('File.php');
// tree class
include_once(SF_BASE_DIR . 'modules/common/includes/Tree.php');

class action_navigation_add_node extends action
{
    /**
     * Add a navigation node (title and body)
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
        $this->B->tree->addNode( $data );

        // Update navigation node config file
        // see modules/common/actions/class.action_common_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $this->B->tree->node,
                  'file'     => SF_BASE_DIR . 'data/navigation/nodes.php',
                  'var_name' => 'node',
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
        
        return TRUE;
    }     
}

?>