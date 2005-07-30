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
 * ActionMiscAddText
 *
 *
 */

class ActionMiscAddText extends SmartAction
{ 
    /**
     * Fields and the format of each of the db table
     *
     */
    protected $tblFields_text = 
                      array('id_text'      => 'Int',
                            'status'       => 'Int',
                            'format'       => 'Int',
                            'media_folder' => 'String',
                            'title'        => 'String',
                            'description'  => 'String',
                            'body'         => 'String');
                            
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
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}misc_text
                   ($fields)
                  VALUES
                   ($quest)";

        $stmt = $this->model->dba->prepare($sql);                    
        
        foreach($data['fields'] as $key => $val)
        {
            $methode = 'set'.$this->tblFields_text[$key];
            $stmt->$methode($val);
        }
        
        $stmt->execute();
        
        // get id of the new text
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
            if(!isset($this->tblFields_text[$key]))
            {
                throw new SmartModelException("Field '".$key."' isnt allowed!");
            }
        }

        return TRUE;
    }  
}

?>