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
     * $data['var']           - array name where to store navigation array
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        // get var name defined in the public template to store the result
        $_result = & $this->B->$data['nav']; 
        
        // load navigation nodes
        $nav = array();
        include(SF_BASE_DIR . 'data/navigation/nodes.php');
        
        foreach($nav as $node)
        {
            list($nodeID, $val) = each($node);
            $_result[] = array('node'   => $nodeID, 
                               'title'  => $node[$nodeID]['title'],
                               'status' => $node[$nodeID]['status']);
        }
    }  
    
}

?>
