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
 * ActionMiscRemoveKeyword class 
 *
 * remove id_text related id_key
 *
 * USAGE:
 *
 * $model->action('misc','removeKeyword',
 *                array('id_text' => int,
 *                      'id_key'  => int));
 *
 */
 
class ActionMiscRemoveKeyword extends SmartAction
{
    private $sqlText = '';
    private $sqlKey     = '';
    
    /**
     * delete article related key
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {         
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}misc_keyword
                  WHERE
                   {$this->sqlText}
                   {$this->sqlKey}";

        $this->model->dba->query($sql);   
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    {         
        if(isset($data['id_text']))
        {
            if(!is_int($data['id_text']))
            {
                throw new SmartModelException('"id_text" isnt from type int');        
            }   
            $this->sqlText = "`id_text`={$data['id_text']}";
            $selcetedItem = TRUE;
        }    
        
        if(isset($data['id_key'])) 
        {
            if(!is_int($data['id_key']))
            {
                throw new SmartModelException("'id_key' isnt from type int");
            }  
            if(isset($selcetedItem))
            {
                $this->sqlKey = " AND ";
            }
            $this->sqlKey .= "`id_key`={$data['id_key']}";
            $selcetedItem  = TRUE;
        }

        if(!isset($selcetedItem))
        {
            throw new SmartModelException('Whether "id_key" nor "id_text" is defined');                      
        }
         
        return TRUE;
    }
}

?>
