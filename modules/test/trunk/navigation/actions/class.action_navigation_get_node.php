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
 
// We need PEAR File to read the nodes file 
include_once('File.php');

class action_navigation_get_node extends action
{
    /**
     * Fill up variables with navigation node title and text
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $this->fp = new File();
        
        // location of the node body (text)
        $node  = SF_BASE_DIR . 'data/navigation/'.$data['node'];
        
        // assign the variable with the node text
        $this->B->$data['body'] = $this->fp->readAll( $node );
        
        // load navigation nodes
        $nav = array();
        include(SF_BASE_DIR . 'data/navigation/nodes.php');
        
        // Look at the node id and assign the title of the requested node
        foreach($nav as $node)
        {
            list($nodeID, $val) = each($node);
            
            // check status request
            if( isset( $data['status'] )  && ($node[$nodeID]['status'] == $data['status']) )
            {
                if($data['node'] == $nodeID)
                {
                    $this->B->$data['title']  = $node[$nodeID]['title'];
                    $this->B->$data['status'] = $node[$nodeID]['status'];
                    break;
                }
            }
            else
            {
                if($data['node'] == $nodeID)
                {
                    $this->B->$data['title']  = $node[$nodeID]['title'];
                    $this->B->$data['status'] = $node[$nodeID]['status'];
                    break;
                }
            }            
        } 
        
        // apply nl2br on result body
        if ($data['nl2br'] == TRUE)
        {
            $this->B->$data['body'] = nl2br($this->B->$data['body']);
        }      
    }   
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    function validate(  $data = FALSE  )
    {
        // validate $data['node']. no chars else than 0123456789 and - are accepted
        if( preg_match("/[^0-9-]/", $data['node']) )
        {
            $this->B->$data['error']  = 'Wrong node format';
            return FALSE;
        }   
        
        // check if the requested node exsists
        
        // load navigation nodes
        $nav = array();
        include(SF_BASE_DIR . 'data/navigation/nodes.php');

        foreach($nav as $node)
        {
            list($nodeID, $val) = each($node);
            
            // check status request
            if( isset( $data['status'] ) && ($node[$nodeID]['status'] == $data['status']) )
            {
                if($data['node'] == $nodeID)
                {
                    return TRUE;
                    break;
                }
            }
            else
            {
                if($data['node'] == $nodeID)
                {
                    return TRUE;
                    break;
                }
            }             
        } 

        $this->B->$data['error']  = 'The requested node dosent exsists or is in drawt mode';
        return FALSE;
    }        
}

?>