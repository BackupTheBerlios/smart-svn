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
            list($id, $val) = each($node);
            if($data['node'] == $id)
            {
                $this->B->$data['title']  = $node[$id]['title'];
                $this->B->$data['status'] = $node[$id]['status'];
                break;
            }
        } 
        
        // apply htmlspecialchars on result body and title
        if ($data['htmlspecialchars'] == TRUE)
        {
            $this->B->$data['body']  = htmlspecialchars($this->B->$data['body']);
            $this->B->$data['title'] = htmlspecialchars($this->B->$data['title']);
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
            return FALSE;
        }     
        
        return TRUE;
    }        
}

?>