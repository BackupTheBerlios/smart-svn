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
 * ActionNavigationAddNode
 *
 * USAGE:
 * 
 * $model->action('navigation','addNode',
 *                array('id_parent' => int,
 *                      'fields' => array('id_view'      => Int,
 *                                        'status'       => Int,
 *                                        'format'       => Int,
 *                                        'logo'         => String,
 *                                        'media_folder' => String,
 *                                        'title'        => String,
 *                                        'body'         => String
 *                                        'short_text'   => String,
 *                                        'lang'         => String)));
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
        $comma  = "";
        $fields = "";
        $quest  = "";
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`";
            $quest  .= $comma."'".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }    

        // id_sector is required
        $fields    .= $comma.'`id_sector`';
        $id_sector  = $this->getIdSector( $data['id_parent'] );
        $quest     .= $comma.$id_sector;
        
        // id_parent is required
        $fields .= $comma.'`id_parent`';
        $quest  .= $comma.$data['id_parent']; 
        
        // id_parent is required
        $fields .= $comma.'`rank`';
        $quest  .= $comma.$this->getRank( $data['id_parent'] );         
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}navigation_node
                   ($fields)
                  VALUES
                   ($quest)";

        $this->model->dba->query($sql);                    

        // get id of the new node
        $new_id_node = $this->model->dba->lastInsertID();
       
        // if the new node is a top node set the node id as sector id
        if($id_sector == 0)
        {
            $this->setIdSector();
        }
        
        // update node index
        $this->model->action('navigation','createIndex',
                              array('id_node' => (int)$new_id_node) );

        return $new_id_node;
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
            throw new SmartModelException('"id_parent" is required');        
        }      
        elseif(!is_int($data['id_parent']))
        {
            throw new SmartModelException('"id_parent" isnt from type int');        
        }      
        
        return TRUE;
    }  

    /**
     * get id_sector of a node
     *
     */    
    private function getIdSector( $id_node = 0 )
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
    /**
     * get rank number for the new added node
     *
     * @param int $id_parent Parent ID
     */    
    private function getRank( $id_parent )
    {
        $sql = "
            SELECT
                `rank`
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_parent`={$id_parent} 
            ORDER BY `rank` DESC
            LIMIT 1";
        
        $rs = $this->model->dba->query($sql);
        $row = $rs->fetchAssoc();
        
        if(!isset($row['rank']))
        {
            return 0;
        }
        
        return ++$row['rank'];
    }    
    
    /**
     * set id_sector of the new node
     *
     */    
    private function setIdSector()
    {
        $id_node = $this->model->dba->lastInsertID();
        
        $sql = "UPDATE {$this->config['dbTablePrefix']}navigation_node
                SET
                   `id_sector`={$id_node}
                WHERE
                   `id_node`={$id_node}";   
        
        $rs = $this->model->dba->query($sql);
    }
}

?>