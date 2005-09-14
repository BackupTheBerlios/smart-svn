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
 * ActionLinkUpdateLink class 
 *
 * USAGE:
 * $model->action('link','updateLink',
 *                array('id_link' => int,
 *                      'fields'  => array('id_node'      => 'Int',
 *                                         'status'       => 'Int',
 *                                         'title'        => 'String',
 *                                         'description'  => 'String',
 *                                         'url'          => 'String',
                                           'hits'         => 'Int',)))
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
            $methode = 'set'.$this->tblFields_link[$key];
            $stmt->$methode($val);
        }
       
        $stmt->execute();             
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
