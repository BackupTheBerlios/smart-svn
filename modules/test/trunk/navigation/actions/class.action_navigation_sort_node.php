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
        if(!is_array($this->B->node))
        {
            // load navigation nodes  
            $node = array();
            include ( SF_BASE_DIR . 'data/navigation/nodes.php' ); 
            $this->B->node = & $node;     
        }         

        // get node data
        if ( TRUE == $this->changeNodeOrder( $data ) )
        {
            // Update navigation node title
            // see modules/common/actions/class.action_common_sys_update_config.php
            M( SF_BASE_MODULE, 
               'sys_update_config', 
               array( 'data'     => $this->B->node,
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
    /**
     * move node order
     *
     * @param array $data
     * @return bool
     */     
    function changeNodeOrder( & $data )
    {
        $parent_id = $this->B->node[$data['node']]['parent_id'];
        $order     = $this->B->node[$data['node']]['order'];

        if( $data['dir'] == 'up' )
        {
            if($order == 1)
            {
                return FALSE;
            }
            
            $new_order = $order - 1;
            
            foreach ($this->B->node as $node => $val)
            {
                if( ($val['order'] == $new_order) && ($val['parent_id'] == $parent_id) )
                {
                    $this->B->node[$node]['order'] = $this->B->node[$node]['order'] + 1;
                    $this->B->node[$data['node']]['order'] = $new_order;
                    return TRUE;
                }
            }
        }
        elseif( $data['dir'] == 'down' )
        {
            if($order == $this->getLastOrderId( (int)$parent_id ))
            {
                return FALSE;
            }
            
            $new_order = $order + 1;
            
            foreach ($this->B->node as $node => $val)
            {
                if( ($val['order'] == $new_order) && ($val['parent_id'] == $parent_id) )
                {
                    $this->B->node[$node]['order'] = $this->B->node[$node]['order'] - 1;
                    $this->B->node[$data['node']]['order'] = $new_order;
                    return TRUE;
                }
            }        
        }   
        
        return FALSE;
    } 
    
    /**
     * get order id of the last node
     *
     * @param int $parent_id
     * @return int
     */     
    function getLastOrderId( $parent_id )
    {
        $order_id = 1;
        foreach ($this->B->node as $key => $val)
        {
            if( $val['parent_id'] == $parent_id )
            {
                if($this->B->node[$key]['order'] >= $order_id)
                {
                    $order_id = $this->B->node[$key]['order'] + 1;
                }
            }
        }  
        
        return $order_id;
    }      
}

?>