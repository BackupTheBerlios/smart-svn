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
 * action_navigation_get_childs class 
 *
 */

class action_navigation_get_branch extends action
{
    /**
     * Fill up an array with navigation nodes of a node branch
     *
     * Structure of the $data array:
     * $data['result'] - name of the navigation array result
     * $data['status'] - status of the nodes to get
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    {
        // get var name defined in the public view to store the result
        $_result = & $this->B->$data['result']; 
        $_result = array();

        $this->status = NULL;
        if( isset($data['status']) )
        {
            $this->status = $data['status'];
        }

        // check if a tree object exists
        if(!is_array($this->B->node))
        {
            // load navigation nodes  
            $node = array();
            include ( SF_BASE_DIR . 'data/navigation/nodes.php' ); 
            $this->B->node = & $node;     
        }        

        // if node is not defined get top level nodes
        if(!isset($data['node']))
        {
            $this->B->$data['node_title'] = FALSE;
            $data['node'] = 0;
        }
        else
        {
            $this->B->$data['node_title'] = $this->B->node[$data['node']]['title'];
        }

        // get child nodes of a given node id
        $this->getBranch( $data['node'] ); 
        
        $_result = $this->branch;
        $this->branch = array();
        $this->_x = 0;
           
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
   
        
        return TRUE;
    } 
    /**
     * get branch (all parents) data of a given node
     *
     * @param int $node
     */     
    function getBranch( $node )
    {
        if(!isset($this->branch))
        {
            $this->branch = array();
            $this->_x = 0;
        }
        
        $parent_id = $this->B->node[$node]['parent_id'];   
        
        if( $parent_id == 0 )
        {
            $this->_x = 0;
            krsort( $this->branch );
            return;
        }
        
        $result = array();
        
        foreach ($this->B->node as $_node => $val)
        {
            if( $parent_id == $_node )
            {
                if(!empty( $this->status ))
                {
                    if( $this->status != $this->B->node[$_node]['status'] )
                    {
                        return;
                    }
                }
                $this->branch[$this->_x]['node']  = $_node;
                $this->branch[$this->_x]['title'] = $this->B->node[$_node]['title'];
                $this->_x++;
                $this->getBranch( $_node );
            }
        }  
    }     
}

?>