<?php

class Tree
{
    var $node = array();
    
    function Tree( & $file )
    {
        include_once ($file);
        $this->node = & $node;
    }

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

    function & getAllData( & $node_id )
    {
        // We need PEAR File to read the nodes file 
        include_once('File.php');

        $fp = & new File();
        
        // location of the node body (text)
        $node_file  = SF_BASE_DIR . 'data/navigation/'.$node_id;

        $result['body']      = $fp->readAll( $node_file );
        $result['node']      = $node_id;
        $result['title']     = $this->node[$node_id]['title'];
        $result['status']    = $this->node[$node_id]['status'];
        $result['order']     = $this->node[$node_id]['order'];
        $result['parent_id'] = $this->node[$node_id]['parent_id'];

        return $result;
    }

    function updateNode( & $data )
    {
        // We need PEAR File to read the nodes file 
        include_once('File.php');

        $fp = & new File();

        // Add navigation node body
        $node_body  = SF_BASE_DIR . 'data/navigation/'.$data['node']; 
        
        if (!is_int($fp->write  ( $node_body, $data['body'], FILE_MODE_WRITE )))
        {
            $this->B->$data['error'] = 'Could not write file: '.$node_body;
            return FALSE;
        }
        
        $fp->unlock ( $node_body, FILE_MODE_WRITE );      
        
        $this->node[$data['node']]['title']     = $data['title'];
        $this->node[$data['node']]['status']    = $data['status'];
        
        if($this->node[$data['node']]['parent_id'] != $data['parent_id'])
        {
            $this->_move = TRUE;
            $this->_verifyParentId( $data['node'], $data['parent_id'] );
            
            if($this->_move == TRUE)
            {

                $tmp = array();
                $tmp['node'] = $this->node[$data['node']]['parent_id'];
                
                $this->node[$data['node']]['order'] = $this->getLastOrderId( $data['parent_id'] );
                $this->node[$data['node']]['parent_id'] = $data['parent_id'];
                
        
                $_data = $this->getChildren( $tmp );
        
                $_order = 1;
        
                foreach ($_data as $node => $val)
                {
                    $this->node[$node]['order'] = $_order;
                    $_order++;
                }                  
            }
        }
        
    } 
    
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
                $this->_verifyParentId( $parent_id, $this->node[$check_id]['parent_id'] );
            }
            return;
    }

    function deleteNode( $node_id )
    {
        // Add navigation node body
        $node_body  = SF_BASE_DIR . 'data/navigation/'.$node_id; 
        
        if (!@unlink( $node_body ))
        {
            trigger_error ('Could not unlink file: '.$node_body, E_USER_ERROR);
        }
        
        $tmp = array();
        $tmp['node'] = $this->node[$node_id]['parent_id'];

        unset( $this->node[$node_id] );
        
        $this->deleteTree( $node_id );
        
        $_data = $this->getChildren( $tmp );
        
        $_order = 1;
        
        foreach ($_data as $node => $val)
        {
            $this->node[$node]['order'] = $_order;
            $_order++;
        }        
    }  

    function deleteTree( $parent_id )
    {
        foreach($this->node as $node => $val)
        {
            if( $val['parent_id'] == $parent_id )
            {
                unset($this->node[$node]);
                
                // Add navigation node body
                $node_body  = SF_BASE_DIR . 'data/navigation/'.$node; 
        
                if (!@unlink( $node_body ))
                {
                    trigger_error ('Could not unlink file: '.$node_body, E_USER_ERROR);
                }
                
                $this->deleteTree( $node );
            }
        }  
        return;
    }

    function updateTreeNodeStatus( $parent_id, $status )
    {
        foreach($this->node as $node => $val)
        {
            if( $val['parent_id'] == $parent_id )
            {
                $this->node[$node]['status'] = $status;
                
                $this->updateTreeNodeStatus( $node, $status );
            }
        }  
        return;
    }

    function changeNodeOrder( & $data )
    {
        $parent_id = $this->node[$data['node']]['parent_id'];
        $order     = $this->node[$data['node']]['order'];

        if( $data['dir'] == 'up' )
        {
            if($order == 1)
            {
                return FALSE;
            }
            
            $new_order = $order - 1;
            
            foreach ($this->node as $node => $val)
            {
                if( ($val['order'] == $new_order) && ($val['parent_id'] == $parent_id) )
                {
                    $this->node[$node]['order'] = $this->node[$node]['order'] + 1;
                    $this->node[$data['node']]['order'] = $new_order;
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
            
            foreach ($this->node as $node => $val)
            {
                if( ($val['order'] == $new_order) && ($val['parent_id'] == $parent_id) )
                {
                    $this->node[$node]['order'] = $this->node[$node]['order'] - 1;
                    $this->node[$data['node']]['order'] = $new_order;
                    return TRUE;
                }
            }        
        }   
        
        return FALSE;
    }

}

?>