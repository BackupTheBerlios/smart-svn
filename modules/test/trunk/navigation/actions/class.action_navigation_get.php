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
 * action_navigation_get class 
 *
 */

// tree class
include_once(SF_BASE_DIR . 'modules/common/includes/Tree.php');

class action_navigation_get extends action
{
    /**
     * Fill up an array with navigation elements
     *
     * Structure of the $data array:
     * $data['nav'] - name of the navigation array
     * $data['status'] - status of the nodes to get
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    {
        // get var name defined in the public view to store the result
        $_result = & $this->B->$data['nav']; 
        $_result = array();

        // if node is not defined get top level nodes
        if(!isset($data['node']))
        {
            $node_id = 0;
        }
        else
        {
            $node_id = $data['node'];
        }

        // check if a tree object exists
        if(!is_object($this->B->tree))
        {
            // load navigation nodes
            $node = array();
            include(SF_BASE_DIR . 'data/navigation/nodes.php');     
            
            $this->B->tree = &Tree::createFromArray($node);
        }        


        // get child nodes of a given node id
        $childs = $this->B->tree->getChildren( $node_id ); 
        
        // fetch data of child nodes
        foreach($childs as $id)
        {
            // get node data
            $ndata = $this->B->tree->getData( $id ); 
            
            // check status request
            if( isset( $data['nstatus'] ) && ($ndata['status'] != $data['nstatus']) )
            {
                continue;
            }
            else
            {            
                // assign the array with the nodes data > ordered!
                $_result[$ndata['order']] = array( 'node'   => $ndata['id'], 
                                                   'title'  => $ndata['title'],
                                                   'status' => $ndata['status']);
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
        // validate $data['status']. "publish" or "drawt" are accepted
        if( isset($data['status']) && !preg_match("/publish|drawt/", $data['status']) )
        {
            trigger_error("Wrong 'status' variable: ".$data['status']." Only 'publish' or 'drawt' are accepted.\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }     
        
        return TRUE;
    }       
}

?>