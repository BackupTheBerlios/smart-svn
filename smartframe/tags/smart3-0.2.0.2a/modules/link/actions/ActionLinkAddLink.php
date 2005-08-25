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
 * ActionLinkAddLink
 *
 * USAGE:
 * 
 * $model->action('link','addLink',
 *                array('id_node' => int,                              // required
 *                      'fields'  => array('status'      => Int,       // optional
 *                                         'title'       => String,    // required
 *                                         'description' => String,    // optional
 *                                         'url'         => String,    // required
 *                                         'hits'        => String))); // optional
 *
 *
 * return new id_link (int)
 */



class ActionLinkAddLink extends SmartAction
{  
    /**
     * Allowed link fields and its type
     */
    protected $tblFields_link = array('id_link'     => 'Int',
                                      'status'      => 'Int',
                                      'title'       => 'String',
                                      'description' => 'String',
                                      'url'         => 'String',
                                      'hits'        => 'Int');
    /**
     * Add link
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
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}link_links
                   ($fields)
                  VALUES
                   ($quest)";

        $stmt = $this->model->dba->prepare($sql);                    
        
        foreach($data['fields'] as $key => $val)
        {
            $methode = 'set'.$this->tblFields_link[$key];
            $stmt->$methode($val);
        }
        
        $stmt->execute();
        
        // get id of the new link
        $new_id_link = $this->model->dba->lastInsertID();
       
        // insert new node-link relation
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}link_node_rel
                   (id_node,id_link)
                  VALUES
                   ({$data['id_node']},{$new_id_link})";

        $this->model->dba->query($sql);     
       
        return $new_id_link;
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
            if(!isset($this->tblFields_link[$key]))
            {
                throw new SmartModelException("Field '".$key."' isnt allowed!");
            }
        }

        if(!isset($data['id_node'])) 
        {
            throw new SmartModelException("'id_node' isnt defined");
        }
        elseif(!is_int($data['id_node']))
        {
            throw new SmartModelException("'id_node' isnt from type int");
        }         
        elseif($data['id_node'] == 0)
        {
            throw new SmartModelException("id_node=0 isnt allowed");
        }  
        
        return TRUE;
    }  
}

?>