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
 * action_navigation_update_node class 
 *
 */
// tree class
include_once(SF_BASE_DIR . 'modules/common/includes/Tree.php');

class action_navigation_delete_node extends action
{
    /**
     * Update navigation node title and body
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        // check if an tree instance exists
        if(!is_object($this->B->tree))
        {
            // load navigation nodes
            $node = array();
            include_once(SF_BASE_DIR . 'data/navigation/nodes.php');
        
            $this->B->tree = &Tree::createFromArray($node);
        }  
        
        $ndata = $this->B->tree->getData( $data['node'] );

        // Update navigation node body
        if (FALSE == @unlink (SF_BASE_DIR . 'data/navigation/'.$ndata['node']))
        {
            $this->B->$data['error'] = 'Could not delete node file: '.SF_BASE_DIR . 'data/navigation/'.$ndata['node'];
            return FALSE;
        }

        // get parent id of the node to reorder
        $parent_id = $this->B->tree->getParentID($data['node']);

        $ids = $this->B->tree->getChildren( $parent_id );
        $_order = 1;
        foreach($ids as $id)
        {
            $tndata = $this->B->tree->getData( $id ); 
            if($ndata['order'] < $tndata['order'])
            {
                $tndata['order']--;
                $this->B->tree->setData( $tndata['id'], $tndata );
            }
        }  
        
        $this->B->tree->removeNode( $data['node'] );
        
        // Update navigation node title
        // see modules/common/actions/class.action_common_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $this->B->tree->data,
                  'file'     => SF_BASE_DIR . 'data/navigation/nodes.php',
                  'var_name' => 'node',
                  'type'     => 'PHPArray') );
        
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
        // validate $data['node']. no chars else than 0123456789 and - are accepted
        if( preg_match("/[^0-9-]/", $data['node']) )
        {
            return FALSE;
        }     
        
        return TRUE;
    }     
}

?>