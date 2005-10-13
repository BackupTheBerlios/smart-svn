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
 * ActionArticleDeleteNodeRelatedContent class 
 *
 * USAGE:
 *
 * $model->action('article','deleteNodeRelatedContent',
 *                array('id_node' => int))
 *
 *
 * DEPENDENCIES:
 * - $this->model->action('article','deleteArticle');
 *
 */
 
class ActionArticleDeleteNodeRelatedContent extends SmartAction
{
    /**
     * delete navigation node related articles
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {  
        $sql = "SELECT 
                    `id_article` 
                FROM {$this->config['dbTablePrefix']}article_article
                WHERE
                   `id_node`={$data['id_node']}";
                   
        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {
            $this->model->action('article','deleteArticle',
                                 array('id_article' => (int)$row['id_article'],
                                       'id_node'    => (int)$data['id_node']));
        }             
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
