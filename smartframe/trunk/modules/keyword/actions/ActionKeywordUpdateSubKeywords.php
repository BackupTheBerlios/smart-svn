<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ActionKeywordUpdateSubKeywords class 
 * Update data of subkeywords
 *
 * USAGE:
 *
   $this->model->action('keyword','updateSubKeywords',
                        array('id_key' => int,
                              'fields' => array('status'    => int,
                                                'id_sector' => int)));
 *
 *
 */
 
class ActionKeywordUpdateSubKeywords extends SmartAction
{
    /**
     * Fields and the format of each of the db table keyword 
     *
     */
    private $tblFields_keyword = 
                      array('id_parent'    => 'Int',
                            'status'       => 'Int');
                            
    /**
     * update data of subkeywords
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    { 
        $tree = array();
        // get subkeywords of a given keyword
        $this->model->action('keyword','getTree', 
                             array('id_key' => $data['id_key'],
                                   'result' => & $tree,
                                   'fields' => array('id_parent','status','id_key')));   
        if( count($tree) > 0 )
        {
            // update subkeywords
            foreach($tree as $node)
            {
                $this->model->action('keyword','update', 
                                     array('id_key' => (int)$node['id_key'],
                                           'fields' => $data['fields'] ));              
            }
        }
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true else throw an exception
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_key']))
        {
            throw new SmartModelException('Action data var "id_key" isnt defined');        
        }
        if(!is_int($data['id_key']))
        {
            throw new SmartModelException('Action data var "id_key" isnt from type int');        
        }        
        
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_keyword[$key]))
            {
                throw new SmartModelException("Field '".$key."' isnt allowed to update!");
            }
        }
        
        return TRUE;
    }  
}

?>
