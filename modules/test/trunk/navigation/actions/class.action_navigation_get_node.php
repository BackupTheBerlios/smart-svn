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
 
// PEAR File
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
        $this->B->$data['body'] = nl2br($this->fp->readAll( $node ));
        
        // load navigation nodes
        $nav = array();
        include(SF_BASE_DIR . 'modules/navigation/_nav_data/nav_data.php');
        
        // Look at the node id and assign the title of the requested node
        foreach($nav as $node)
        {
            list($id, $val) = each($node);
            if($data['node'] == $id)
            {
                $this->B->$data['title'] = $node[$key]['title'];
                break;
            }
        } 
    }     
}

?>
