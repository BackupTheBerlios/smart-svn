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
     * $data['status'] - status of the nodes to get
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
            if($node == 0)
            {
                continue;
            }
            list($nodeID, $val) = each($node);
            
            // check status request
            if( isset( $data['status'] ) )
            {
                // only assign nodes which matchs the status
                if ( $node[$nodeID]['status'] == $data['status'] )
                {
                    // assign the array with the nodes data
                    $_result[] = array( 'node'   => $nodeID, 
                                        'title'  => $node[$nodeID]['title'],
                                        'status' => $node[$nodeID]['status']);
                }
            }
            else
            {
                // assign the array with all nodes
                $_result[] = array( 'node'   => $nodeID, 
                                    'title'  => $node[$nodeID]['title'],
                                    'status' => $node[$nodeID]['status']);
            
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