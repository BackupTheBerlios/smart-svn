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
            $node = array();
            include(SF_BASE_DIR . 'data/navigation/nodes.php');     
            
            $this->B->tree = &Tree::createFromArray($node);
        }        

        $ndata = $this->B->tree->getData( $data['node'] ); 
        
        $parent_id = $this->B->tree->getParentID($data['node']);
        
        $ids = $this->B->tree->getChildren( $parent_id );
            
       // move up
        if( $data['dir'] == 'up' )
        {
            $new_order_num = $ndata['order']-1;
            foreach($ids as $id)
            {
                $tndata = $this->B->tree->getData( $id ); 
                if($tndata['order'] == $new_order_num)
                {
                    $tndata['order'] = $tndata['order']+1;
                    $tndata['parent_id'] = $parent_id;
                    $node_id = $tndata['id'];
                    $ndata['order']  = $new_order_num;
                    $ndata['parent_id'] = $parent_id;
                    $found = TRUE;
                    break;
                }
            }
            
            if(isset($found))
            {
                $this->B->tree->setData( $data['node'], $ndata ); 
                $this->B->tree->setData( $node_id, $tndata ); 

                // Update navigation node title
                // see modules/common/actions/class.action_common_sys_update_config.php
                M( SF_BASE_MODULE, 
                   'sys_update_config', 
                   array( 'data'     => $this->B->tree->data,
                          'file'     => SF_BASE_DIR . 'data/navigation/nodes.php',
                          'var_name' => 'node',
                          'type'     => 'PHPArray') );
            }          
        }
        // move down
        elseif( $data['dir'] == 'down' )
        {
            $new_order_num = $ndata['order']+1;
            foreach($ids as $id)
            {
                $tndata = $this->B->tree->getData( $id ); 
                if($tndata['order'] == $new_order_num)
                {
                    $tndata['order'] = $tndata['order']-1;
                    $tndata['parent_id'] = $parent_id;
                    $node_id = $tndata['id'];
                    $ndata['order']  = $new_order_num;
                    $ndata['parent_id'] = $parent_id;
                    $found = TRUE;
                    break;
                }
            }
            
            if(isset($found))
            {
                $this->B->tree->setData( $data['node'], $ndata ); 
                $this->B->tree->setData( $node_id, $tndata ); 

                // Update navigation node title
                // see modules/common/actions/class.action_common_sys_update_config.php
                M( SF_BASE_MODULE, 
                   'sys_update_config', 
                   array( 'data'     => $this->B->tree->data,
                          'file'     => SF_BASE_DIR . 'data/navigation/nodes.php',
                          'var_name' => 'node',
                          'type'     => 'PHPArray') );
            }     
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