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
        if(!is_array($this->B->node))
        {
            // load navigation nodes  
            $node = array();
            include ( SF_BASE_DIR . 'data/navigation/nodes.php' ); 
            $this->B->node = & $node;     
        }  
        
        // add node to the array
        $this->addNode( $data );

        // Update navigation node config file
        // see modules/common/actions/class.action_common_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $this->B->node,
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
    /**
     * add node data
     *
     * @param array $data
     */      
    function addNode( & $data )
    {
        $node_id = $this->createUniqueId();
        
        // We need PEAR File to read the nodes file 
        include_once('File.php');

        $fp = & new File();

        // Add navigation node body
        $node_body  = SF_BASE_DIR . 'data/navigation/'.$node_id; 
        
        if (!is_int($fp->write  ( $node_body, commonUtil::stripSlashes( $data['body'] ), FILE_MODE_WRITE )))
        {
            $this->B->$data['error'] = 'Could not write file: '.$node_body;
            return FALSE;
        }
        
        $fp->unlock ( $node_body, FILE_MODE_WRITE );      
        
        $this->B->node[$node_id]['title']     = commonUtil::stripSlashes( $data['title'] );
        $this->B->node[$node_id]['status']    = $data['status'];
        $this->B->node[$node_id]['order']     = $this->getLastOrderId( (int)$data['parent_id'] );
        $this->B->node[$node_id]['parent_id'] = (int)$data['parent_id'];
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
     * create unique node id
     *
     * @return int
     */     
    function & createUniqueId()
    {
        // make node id
        $node_id = commonUtil::unique_crc32();
        
        while( isset($this->B->node[$node_id]) )
        {
            $node_id = commonUtil::unique_crc32();
        }
        
        return $node_id;
    }    
}

?>