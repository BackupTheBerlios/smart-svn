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
        if(!is_array($this->B->node))
        {
            // load navigation nodes  
            $node = array();
            include ( SF_BASE_DIR . 'data/navigation/nodes.php' ); 
            $this->B->node = & $node;     
        }      

        // load data of the requested node
        $this->updateNode( $data ); 

        // Update navigation node title
        // see modules/common/actions/class.action_common_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $this->B->node,
                  'file'     => SF_BASE_DIR . 'data/navigation/nodes.php',
                  'var_name' => 'node',
                  'type'     => 'PHPArray') );

        // Delete cache data
        M( MOD_COMMON, 'cache_delete', array( 'id'    => 'public'.$data['node'],
                                              'group' => 'navigation'));  
        // Delete cache data
        M( MOD_COMMON, 'cache_delete', array('group' => 'navigation-tree'));                                                
        
        return SF_IS_VALID_ACTION;
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
            return SF_NO_VALID_ACTION;
        }        
        // validate $data['status']. it should be "drawt" or "publish"
        if( !is_int($data['status']) || ($data['status'] < 0) || ($data['status'] > 2) )
        {
            $this->B->$data['error'] = 'Wrong status format !!!';        
            return SF_NO_VALID_ACTION;
        }
        // validate $data['node']. no chars else than 0123456789 and - are accepted
        if( preg_match("/[^0-9-]/", $data['node']) )
        {
            $this->B->$data['error'] = 'Wrong node format !!!';        
            return SF_NO_VALID_ACTION;
        }     
        
        return SF_IS_VALID_ACTION;
    }   
    /**
     * update node data
     *
     * @param array $data
     */    
    function updateNode( & $data )
    {
        // We need PEAR File to read the nodes file 
        include_once('File.php');

        $fp = & new File();

        // Add navigation node body
        $node_body  = SF_BASE_DIR . 'data/navigation/'.$data['node']; 
        
        if (!is_int($fp->write  ( $node_body, commonUtil::stripSlashes( $data['body'] ), FILE_MODE_WRITE )))
        {
            $this->B->$data['error'] = 'Could not write file: '.$node_body;
            return FALSE;
        }
        
        $fp->unlock ( $node_body, FILE_MODE_WRITE );      
        
        $this->B->node[$data['node']]['title']     = commonUtil::addSlashes( $data['title'] );
        $this->B->node[$data['node']]['status']    = $data['status'];
        
        if($this->B->node[$data['node']]['parent_id'] != (int)$data['parent_id'])
        {
            $this->_move = TRUE;
            $this->_verifyParentId( $data['node'], (int)$data['parent_id'] );
            
            if($this->_move == TRUE)
            {

                $tmp = array();
                $tmp['node'] = $this->B->node[$data['node']]['parent_id'];
                
                $this->B->node[$data['node']]['order'] = $this->getLastOrderId( (int)$data['parent_id'] );
                $this->B->node[$data['node']]['parent_id'] = (int)$data['parent_id'];
                
        
                $_data = $this->getChildren( $tmp );
        
                $_order = 1;
        
                foreach ($_data as $node => $val)
                {
                    $this->B->node[$node]['order'] = $_order;
                    $_order++;
                }                  
            }
        }
        
    } 
    /**
     * check for circular reference error
     *
     * @param int $parent_id
     * @param int $check_id
     */     
    function _verifyParentId( $parent_id, $check_id )
    {
        if( 0 == $check_id )
        {
            return;
        }
        elseif( ($this->_move == FALSE) || ( $parent_id == $check_id) )
        {
            $this->_move = FALSE;
            return;
        }
        else
        { 
            $this->_verifyParentId( $parent_id, $this->B->node[$check_id]['parent_id'] );
        }
        return;
    }

    /**
     * update sub nodes statuses
     *
     * @param int $parent_id
     * @param int $status
     */ 
    function updateTreeNodeStatus( $parent_id, $status )
    {
        foreach($this->B->node as $node => $val)
        {
            if( $val['parent_id'] == $parent_id )
            {
                $this->B->node[$node]['status'] = $status;
                
                $this->updateTreeNodeStatus( $node, $status );
            }
        }  
        return;
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
    
    /**
     * get child nodes sorted by order
     *
     * @param array $data
     */     
    function & getChildren( & $data )
    {
        $tmp = array();
        foreach ($this->B->node as $key => $val)
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
            $result[$val]['title']     = $this->B->node[$val]['title'];
            $result[$val]['status']    = $this->B->node[$val]['status'];
            $result[$val]['order']     = $this->B->node[$val]['order'];
            $result[$val]['parent_id'] = $this->B->node[$val]['parent_id'];
        }  
        
        unset($tmp);

        return $result;
    }        
}

?>