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
 
class ActionLinkUpdateLink extends SmartAction
{
    /**
     * Allowed link fields and its type
     */
    protected $tblFields_link = array('id_link'     => 'Int',
                                      'id_node'     => 'Int',
                                      'status'      => 'Int',
                                      'title'       => 'String',
                                      'description' => 'String',
                                      'url'         => 'String',
                                      'hits'        => 'Int');
                                      
    /**
     * update link
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $comma = '';
        $fields = '';
        
        foreach($data['fields'] as $key => $val)
        {
            if($key == 'id_node')
            {
                continue;
            }
            $fields .= $comma.'`'.$key.'`=?';
            $comma = ',';
        }
        
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}link_links
                SET
                   $fields
                WHERE
                `id_link`={$data['id_link']}";
        
        $stmt = $this->model->dba->prepare($sql);                    
        
        foreach($data['fields'] as $key => $val)
        {
            if($key == 'id_node')
            {
                continue;
            }        
            $methode = 'set'.$this->tblFields_link[$key];
            $stmt->$methode($val);
        }
       
        $stmt->execute();     
        
        // update node-link relation
        $sql = "UPDATE {$this->config['dbTablePrefix']}link_node_rel
                   SET `id_node`={$data['fields']['id_node']}
                  WHERE
                   `id_link`={$data['id_link']}";

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
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_link[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!is_int($data['id_link']))
        {
            throw new SmartModelException('"id_link" isnt from type int');        
        }
        
        return TRUE;
    }
}

?>
