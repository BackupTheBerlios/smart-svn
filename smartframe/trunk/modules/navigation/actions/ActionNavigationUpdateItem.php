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
 * ActionNavigationUpdateItem class 
 *
 * USAGE:
 * $model->action('navigation','updateItem',
 *                array('item' => string,    // pic or file
 *                      'ids'  => & array,   // array with ids of files or pics
 *                      'fields' => array('id_node'     => 'Int',
 *                                        'rank'        => 'Int',
 *                                        'size'        => 'Int',
 *                                        'file'        => 'String',
 *                                        'mime'        => 'String',
 *                                        'title'       => 'String',
 *                                        'description' => 'String') ))
 *
 */
class ActionNavigationUpdateItem extends SmartAction
{
    protected $tblFields_item = 
                      array('id_node'     => 'Int',
                            'rank'        => 'Int',
                            'size'        => 'Int',
                            'file'        => 'String',
                            'mime'        => 'String',
                            'title'       => 'String',
                            'description' => 'String');
                            
    /**
     * update user pictures/files data
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    { 
        $x = 0;
        foreach($data['ids'] as $id)
        {
            $comma  = "";
            $fields = "";
        
            foreach($data['fields'] as $key => $val)
            {
                $fields .= $comma."`".$key."`='".$this->model->dba->escape($val[$x])."'";
                $comma   = ",";
            }

            $sql = "UPDATE {$this->config['dbTablePrefix']}{$this->table}
                      SET
                       $fields
                      WHERE
                       `{$this->tbl_field}`={$id}";

            $this->model->dba->query($sql);    
            $x++;
        }
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
                $this->table                     = 'navigation_media_file';
                $this->tbl_field                 = 'id_file';
                $this->tblFields_item['id_file'] = 'Int';                
                break;
                
            case 'pic':
                $this->table                    = 'navigation_media_pic';
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
