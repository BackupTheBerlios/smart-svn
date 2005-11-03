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
 * ActionNavigationAddKeyword
 *
 * USAGE:
 *
 * $model->action('navigation', 'addKeyword',
 *                array('id_node' => int,
 *                      'id_key'  => int) );
 * 
 */



class ActionNavigationAddKeyword extends SmartAction
{                                           
    /**
     * Add keyword
     *
     */
    public function perform( $data = FALSE )
    {     
        // return if id_key is still related to this id_node 
        if($this->isKey( $data['id_node'], $data['id_key'] ) == 1)
        {
            return;
        }
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}navigation_keyword
                   (`id_key`,`id_node`)
                  VALUES
                   ({$data['id_key']},{$data['id_node']})";

        $this->model->dba->query($sql);                    
    } 
    
    /**
     * validate array data
     *
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['id_node'])) 
        {
            throw new SmartModelException("'id_node' isnt defined");
        }
        elseif(!is_int($data['id_node']))
        {
            throw new SmartModelException("'id_node' isnt from type int");
        }         
          
        if(!isset($data['id_key'])) 
        {
            throw new SmartModelException("'id_key' isnt defined");
        }
        elseif(!is_int($data['id_key']))
        {
            throw new SmartModelException("'id_key' isnt from type int");
        }  
        
        return TRUE;
    }  
    /**
     * check if id_key is still related to id_node
     *
     * @param int $id_node
     * @param int $id_key
     * @return int num Rows
     */
    private function isKey( $id_node, $id_key )
    {         
        $sql = "SELECT SQL_CACHE
                  `id_key`
                FROM 
                  {$this->config['dbTablePrefix']}navigation_keyword
                WHERE
                   `id_node`={$id_node}
                AND
                   `id_key`={$id_key}";

        $result = $this->model->dba->query($sql); 
        return $result->numRows();
    }     
}

?>