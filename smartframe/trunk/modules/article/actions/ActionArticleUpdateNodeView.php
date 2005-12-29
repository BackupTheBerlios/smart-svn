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

 
class ActionArticleUpdateNodeView extends ActionNavigation
{
    /**
     * update navigation node
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        $sql = "REPLACE INTO {$this->config['dbTablePrefix']}article_node_view_rel
                   (`id_node`,`id_view`)
                VALUES
                   ({$data['id_node']},{$data['id_view']})";

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
        if(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }
        if(!isset($data['id_view']))
        {
            throw new SmartModelException('"id_view" isnt defined');        
        }    
        if(!is_int($data['id_view']))
        {
            throw new SmartModelException('"id_view" isnt from type int');        
        }        
        return TRUE;
    }
}

?>
