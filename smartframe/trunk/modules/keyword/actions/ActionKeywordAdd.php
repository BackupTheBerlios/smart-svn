<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ActionKeywordAdd
 *
 * USAGE:
 * 
 * $model->action('keyword','add',
 *                array('fields' => array('id_parent'    => int,
 *                                        'status'       => Int,
 *                                        'title'        => String,
 *                                        'description'  => String)));
 *
 */

class ActionKeywordAdd extends SmartAction
{  
    /**
     * Fields and the format of each of the db table keyword 
     *
     */
    private $tblFields_keyword = 
                      array('id_parent'    => 'Int',
                            'status'       => 'Int',
                            'title'        => 'String',
                            'description'  => 'String');
                            
    /**
     * Add navigation node
     *
     */
    public function perform( $data = FALSE )
    {
        $comma  = "";
        $fields = "";
        $quest  = "";
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`";
            $quest  .= $comma."'".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }                  
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}keyword
                   ($fields)
                  VALUES
                   ($quest)";

        $this->model->dba->query($sql);                    

        // return id of the new keyword
        return $this->model->dba->lastInsertID();
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
            if(!isset($this->tblFields_keyword[$key]))
            {
                throw new SmartModelException("Field '".$key."' isnt allowed!");
            }
        }

        if(!isset($data['fields']['title']))
        {
            throw new SmartModelException('"title" is required');        
        }      
        elseif(!is_string($data['fields']['title']))
        {
            throw new SmartModelException('"title" isnt from type string');        
        }  
        if(!isset($data['fields']['id_parent']))
        {
            throw new SmartModelException('"id_parent" is required');        
        }      
        elseif(!is_int($data['fields']['id_parent']))
        {
            throw new SmartModelException('"id_parent" isnt from type int');        
        }      
        
        return TRUE;
    }  
}

?>