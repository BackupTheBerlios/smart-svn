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

class action_navigation_sort_node extends action
{
    /**
     * Move up or down a navigation node
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

        // get node data
        if ( TRUE == $this->B->tree->changeNodeOrder( $data ) )
        {
            // Update navigation node title
            // see modules/common/actions/class.action_common_sys_update_config.php
            M( SF_BASE_MODULE, 
               'sys_update_config', 
               array( 'data'     => $this->B->tree->node,
                      'file'     => SF_BASE_DIR . 'data/navigation/nodes.php',
                      'var_name' => 'node',
                      'type'     => 'PHPArray') );    
        }
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
        // validate $data['dir']. it should be "up" or "down"
        if( !preg_match("/up|down/", $data['dir']) )
        {
            return FALSE;
        }
        // validate $data['node']. no chars else than 0123456789 and - are accepted
        if( preg_match("/[^0-9-]/", $data['node']) )
        {
            return FALSE;
        }     
        
        return TRUE;
    }
}

?>