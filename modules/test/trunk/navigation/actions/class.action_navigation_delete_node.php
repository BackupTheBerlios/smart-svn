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

class action_navigation_delete_node extends action
{
    /**
     * Update navigation node title and body
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        // check if a tree node array exists
        if(!isset($this->node))
        {
            // load navigation nodes
            include_once SF_BASE_DIR . 'data/navigation/nodes.php';     
            $this->node = & $node;
        }
        
        // add node to the array
        $this->deleteNode( $data['node'] );

        // Update navigation node config file
        // see modules/common/actions/class.action_common_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $this->node,
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
    /**
     * delete node data
     *
     * @param int $node_id node id
     */        
    function deleteNode( $node_id )
    {
        // get node body text file path
        $node_body  = SF_BASE_DIR . 'data/navigation/'.$node_id; 
        // delete body text file
        if (!@unlink( $node_body ))
        {
            trigger_error ('Could not unlink file: '.$node_body, E_USER_ERROR);
        }
        
        // delete node array item
        $tmp = array();
        $tmp['node'] = $this->node[$node_id]['parent_id'];

        unset( $this->node[$node_id] );
        
        // delete subtree
        $this->deleteTree( $node_id );
        
        // get brother nodes from the deleted node
        $_data = $this->getChildren( $tmp );
        
        // and reorder them
        $_order = 1;
        
        foreach ($_data as $node => $val)
        {
            $this->node[$node]['order'] = $_order;
            $_order++;
        }        
    }  
    /**
     * delete tree (subtree)
     *
     * @param int $parent_id parent id
     */      
    function deleteTree( $parent_id )
    {
        foreach($this->node as $node => $val)
        {
            if( $val['parent_id'] == $parent_id )
            {
                // delete node in array
                unset($this->node[$node]);
                
                // get node body text file path
                $node_body  = SF_BASE_DIR . 'data/navigation/'.$node; 
                // delete body text file
                if (!@unlink( $node_body ))
                {
                    trigger_error ('Could not unlink file: '.$node_body, E_USER_ERROR);
                }
                // recursive call
                $this->deleteTree( $node );
            }
        }  
        return;
    }
    /**
     * get child nodes sorted by order
     *
     * @param array $data
     */     
    function & getChildren( & $data )
    {
        $tmp = array();
        foreach ($this->node as $key => $val)
        {
            if( $val['parent_id'] == $data['node'] )
            {
                if( isset($data['status']) )
                {
                    if( $val['status'] == $data['status'] )
                    {
                        $tmp[$val['order']] = $key;
                    }
                    continue;
                }
                $tmp[$val['order']] = $key;
            }
        }
        // ordered
        ksort($tmp);
        
        $result = array();
        
        foreach ($tmp as $val)
        {
            $result[$val]['title']     = $this->node[$val]['title'];
            $result[$val]['status']    = $this->node[$val]['status'];
            $result[$val]['order']     = $this->node[$val]['order'];
            $result[$val]['parent_id'] = $this->node[$val]['parent_id'];
        }  
        
        unset($tmp);

        return $result;
    }    
}

?>