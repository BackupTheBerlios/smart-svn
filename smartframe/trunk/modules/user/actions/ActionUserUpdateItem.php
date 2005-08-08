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
 * ActionNavigationUpdateItem class 
 *
 */
class ActionUserUpdateItem extends SmartAction
{
    protected $tblFields_item = 
                      array('id_user'     => 'Int',
                            'rank'        => 'Int',
                            'title'       => 'String',
                            'description' => 'String');
                            
    /**
     * update user pictures/files data
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    { 

        $comma = '';
        $fields = '';
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma.'`'.$key.'`=?';
            $comma = ',';
        }

        $sql = "UPDATE {$this->config['dbTablePrefix']}{$this->table}
                  SET
                   $fields
                  WHERE
                   `{$this->tbl_field}`=?";

        $stmt = $this->model->dba->prepare($sql);       

        $x = 0;
        foreach($data['ids'] as $id)
        {
            foreach($data['fields'] as $key => $val)
            {
                $methode = 'set'.$this->tblFields_item[$key];
                $stmt->$methode($val[$x]);
            }
        
            // set id_xxx
            $stmt->setInt( $id ); 
        
            $stmt->execute();   
            $x++;
        }
        return TRUE;
    }
    
    /**
     * validate user data
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( $data = FALSE )
    { 
        if(!isset($data['item']))
        {
            throw new SmartModelException ('action array var "item" isnt defined!'); 
        }
        if(!is_string($data['item']))
        {
            throw new SmartModelException ('action array var "item" must be a string!'); 
        }        
        // set table name and item reference
        switch($data['item'])
        {
            case 'file':
                $this->table                     = 'user_media_file';
                $this->tbl_field                 = 'id_file';
                $this->tblFields_item['id_file'] = 'Int';                
                break;
                
            case 'pic':
                $this->table                    = 'user_media_pic';
                $this->tbl_field                = 'id_pic';  
                $this->tblFields_item['id_pic'] = 'Int';
                break;
            default:
                throw new SmartModelException ('"item" must be "file" or "pic". Unknown "item": '.$data['item']); 
        }

        if( !isset($data['ids']) )
        {        
            throw new SmartModelException ('"ids" must be defined'); 
        } 
        elseif(!is_array($data['ids'])  )
        {        
            throw new SmartModelException ('"ids" must be an array'); 
        } 

        if(!isset($data['fields']))
        {
            throw new SmartModelException ('action array var "fields" isnt defined!'); 
        }
        if(!is_array($data['fields']))
        {
            throw new SmartModelException ('action array var "fields" must be an array!'); 
        }  
    
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_item[$key]))
            {
                throw new SmartModelException("Field '".$key."' isnt allowed!");
            }
        }
        
        return TRUE;
    }
}

?>
