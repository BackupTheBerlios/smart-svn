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
 */

 
class ActionArticleUpdateView extends SmartAction
{
    /**
     * update navigation node
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        $sql = "REPLACE INTO {$this->config['dbTablePrefix']}article_view_rel
                   (`id_article`,`id_view`)
                VALUES
                   ({$data['id_article']},{$data['id_view']})";

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
        if(!isset($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt defined');        
        }    
        if(!is_int($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt from type int');        
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
