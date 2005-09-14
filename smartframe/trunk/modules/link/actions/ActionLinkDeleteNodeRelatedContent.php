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
 * ActionLinkDeleteNodeRelatedContent class 
 *
 * USAGE:
 *
 * $model->action('link','linkDeleteNodeRelatedContent',
 *                array('id_node' => int))
 *
 *
 * DEPENDENCIES:
 * - $this->model->action('link','deleteLink');
 *
 */
 
class ActionLinkDeleteNodeRelatedContent extends SmartAction
{
    /**
     * delete navigation node related links
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {  
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}link_links
                  WHERE
                   `id_node`={$data['id_node']}";

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
        if(!isset($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt defined');        
        }    
        elseif(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }
               
        return TRUE;
    }
}

?>
