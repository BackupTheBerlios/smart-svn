<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ActionNavigationUpdateSubNodes class 
 * Update data of subnodes
 *
 * USAGE:
 *
 * $this->model->action('navigation','updateSubNodes',
 *                      array('id_node' => int,
 *                            'fields'  => array('status'    => int,
 *                                               'id_sector' => int)));
 *
 *
 */
 
class ActionNavigationUpdateSubNodes extends SmartAction
{
    /**
     * allowed fields
     */
    protected $tblFields_node = array('id_sector' => 'Int',
                                      'status'    => 'Int',
                                      'id_view'   => 'Int');
    /**
     * update data of subnodes
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    { 
        $tree = array();
        // get subnode of a given node
        $this->model->action('navigation','getTree', 
                             array('id_node' => $data['id_node'],
                                   'result'  => & $tree,
                                   'fields'  => array('id_parent','status','id_node')));   
        if( count($tree) > 0 )
        {
            // update subnodes
            foreach($tree as $node)
            {
                $this->model->action('navigation','updateNode', 
                                     array('id_node' => (int)$node['id_node'],
                                           'fields'  => $data['fields'] ));              
            }
        }
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true else throw an exception
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_node']))
        {
            throw new SmartModelException('Action data var "id_node" isnt defined');        
        }
        if(!is_int($data['id_node']))
        {
            throw new SmartModelException('Action data var "id_node" isnt from type int');        
        }        
        
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_node[$key]))
            {
                throw new SmartModelException("Field '".$key."' isnt allowed to update!");
            }
        }
        
        return TRUE;
    }  
}

?>
