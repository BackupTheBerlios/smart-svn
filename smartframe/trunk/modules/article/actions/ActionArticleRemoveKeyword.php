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
 * ActionArticleDeleteArticle class 
 *
 * USAGE:
 *
 * $model->action('article','deleteArticle',
 *                array('id_article'  => int))
 *
 */
 
class ActionArticleRemoveKeyword extends SmartAction
{
    /**
     * delete article and navigation node relation
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {         
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_key
                  WHERE
                   `id_article`={$data['id_article']}
                  AND
                   `id_key`={$data['id_key']}";

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
        elseif(!is_int($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt from type int');        
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
}

?>
