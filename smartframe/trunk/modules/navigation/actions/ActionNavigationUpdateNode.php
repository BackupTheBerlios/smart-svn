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
 * ActionNavigationUpdateNode class 
 *
 * USAGE:
 * $model->action('navigation','UpdateNode',
 *                array('id_node' => int,
 *                      'fields'  => array('id_node'      => 'Int',
 *                                         'id_parent'    => 'Int',
 *                                         'id_sector'    => 'Int',
 *                                         'id_view'      => 'Int',
 *                                         'status'       => 'Int',
 *                                         'rank'         => 'Int',
 *                                         'format'       => 'Int',
 *                                         'logo'         => 'String',
 *                                         'media_folder' => 'String',
 *                                         'lang'         => 'String',
 *                                         'title'        => 'String',
 *                                         'short_text'   => 'String',
 *                                         'body'         => 'String')))
 */

include_once(SMART_BASE_DIR . 'modules/navigation/includes/ActionNavigation.php');
 
class ActionNavigationUpdateNode extends ActionNavigation
{
    /**
     * update navigation node
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        $comma  = "";
        $fields = "";
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`='".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }
        
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}navigation_node
                SET
                   $fields
                WHERE
                `id_node`={$data['id_node']}";

        $this->model->dba->query($sql);                    
        
        if(isset($data['fields']['title'])       ||
           isset($data['fields']['short_text'])   ||
           isset($data['fields']['body']))
        {
            // update navigation index
            $this->model->action('navigation','createIndex',
                                 array('id_node' => (int)$data['id_node']) );
        }
        
        return TRUE;
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
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
            if(!isset($this->tblFields_node[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }
        
        return TRUE;
    }
}

?>
