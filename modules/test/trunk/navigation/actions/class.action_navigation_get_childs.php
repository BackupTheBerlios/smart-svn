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

// tree class
include_once(SF_BASE_DIR . 'modules/common/includes/Tree.php');

class action_navigation_get_childs extends action
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
        $_result = & $this->B->$data['result']; 
        $_result = array();

        // if node is not defined get top level nodes
        if(!isset($data['node']))
        {
            $data['node'] = 0;
        }

        // check if a tree object exists
        if(!is_object($this->B->tree))
        {
            // load navigation nodes
            $file = SF_BASE_DIR . 'data/navigation/nodes.php';     
            
            $this->B->tree = & new Tree($file);
        }        

        // get child nodes of a given node id
        $_result = $this->B->tree->getChildren( $data ); 
        
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
        if( isset($data['status']) && ( ($data['status'] < 0) || ($data['status'] > 2) ) )
        {
            trigger_error("Wrong 'status' variable: ".$data['status']." Only 'publish' or 'drawt' are accepted.\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }     
        
        return TRUE;
    }       
}

?>