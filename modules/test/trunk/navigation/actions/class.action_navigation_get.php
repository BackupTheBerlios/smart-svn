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

class action_navigation_get extends action
{
    /**
     * Fill up an array with navigation elements
     *
     * Structure of the $data array:
     * $data['nav'] - name of the navigation array
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    {
        // get var name defined in the public view to store the result
        $_result = & $this->B->$data['nav']; 
        
        // load navigation nodes
        $nav = array();
        include(SF_BASE_DIR . 'data/navigation/nodes.php');
        
        foreach($nav as $node)
        {
            list($nodeID, $val) = each($node);
            // assign the array with the nodes data
            $_result[] = array('node'   => $nodeID, 
                               'title'  => $node[$nodeID]['title'],
                               'status' => $node[$nodeID]['status']);
        }
        
        return TRUE;
    }   
}

?>
