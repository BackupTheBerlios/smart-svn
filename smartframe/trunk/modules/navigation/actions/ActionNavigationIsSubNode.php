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
 * ActionNavigationIsSubNode class 
 *
 */
 
class ActionNavigationIsSubNode extends SmartAction
{
    /**
     * check if node $data['id_node1'] is a subnode of node $data['id_node2']
     *
     * @param array $data
     * @return bool true or false
     */
    function perform( $data = FALSE )
    { 
        $this->tree = array();
        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => $data['id_node2'],
                                   'result'  => & $this->tree,
                                   'fields'  => array('id_parent','status','id_node')));   
        
        return $this->isSubNode( $data['id_node1'] );
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true else throw an exception
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_node1']))
        {
            throw new SmartModelException('Action data var "id_node1" isnt defined');        
        }
        
        if(!isset($data['id_node2']))
        {
            throw new SmartModelException('Action data var "id_node2" isnt defined');        
        }   
        
        if(preg_match("/[^0-9]/",$data['id_node2']))
        {
            throw new SmartModelException('Wrong id_node2 format: '.$id_user);        
        }    
        
        if(preg_match("/[^0-9]/",$data['id_node1']))
        {
            throw new SmartModelException('Wrong id_node1 format: '.$id_user);        
        }
        
        if(preg_match("/[^0-9]/",$data['id_node2']))
        {
            throw new SmartModelException('Wrong id_node format: '.$id_user);        
        }    
        
        return TRUE;
    }
    /**
     * check if $id_node is a subnode
     *
     * @param int $id_node
     * @return bool true or false
     */    
    private function isSubNode( $id_node )
    { 
    
        foreach($this->tree as $node)
        {
            if($node['id_node'] == (int)$id_node)
            {
                return TRUE;
            }
        }
        return FALSE;
    }    
}

?>
