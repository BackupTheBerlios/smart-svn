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
 * ActionNavigationAddNode
 *
 *
 */

include_once(SMART_BASE_DIR . 'modules/navigation/includes/ActionNavigation.php');

class ActionNavigationAddNode extends ActionNavigation
{   
    /**
     * Add navigation node
     *
     */
    public function perform( $data = FALSE )
    {                   
        $comma = '';
        $fields = '';
        $quest = '';
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma.'`'.$key.'`';
            $quest  .= $comma.'?';
            $comma   = ',';
        }

        // id_sector is required
        $fields .= $comma.'`id_sector`';
        $quest  .= $comma.'?';
        
        // id_parent is required
        $fields .= $comma.'`id_parent`';
        $quest  .= $comma.'?';        
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}navigation_node
                   ($fields)
                  VALUES
                   ($quest)";

        $stmt = $this->model->dba->prepare($sql);                    
        
        foreach($data['fields'] as $key => $val)
        {
            $methode = 'set'.$this->tblFields_node[$key];
            $stmt->$methode($val);
        }
        
        // set id_sector by retriving the sector of the parent node
        $stmt->setInt( $this->getSector( $data['id_parent'] ) );        
        // set id_parent
        $stmt->setInt( $data['id_parent'] );  
       
        $stmt->execute();
       
        return TRUE;
    } 
    
    /**
     * validate array data
     *
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if($key == 'id_sector')
            {
                throw new SmartModelException("Field 'id_sector' is not allowed!");
            }      
            elseif($key == 'id_parent')
            {
                throw new SmartModelException("Field 'id_parent' is not allowed in fields array!");
            } 
            
            if(!isset($this->tblFields_node[$key]))
            {
                throw new SmartModelException("Field '".$key."' isnt allowed!");
            }
        }

        if(!isset($data['id_parent']))
        {
            throw new SmartModelException('Array value "id_parent" is required');        
        }      
        elseif(preg_match("/[^0-9]/",$data['id_parent']))
        {
            throw new SmartModelException('Wrong "id_parent format": '.$data['id_parent']);        
        }      
        
        return TRUE;
    }  

    /**
     * get id_sector of a node
     *
     */    
    private function getSector( $id_node = 0 )
    {
        if($id_node != 0)
        {
            // get id_sector of the parent node
            $node = array();
            $this->model->action('navigation','getNode',
                                 array('result'  => &$node,
                                       'id_node' => $id_node,
                                       'fields'  => array('id_sector')));
                                       
            return $node['id_sector'];
        }
        else
        {
            return 0;
        }
    }
}

?>