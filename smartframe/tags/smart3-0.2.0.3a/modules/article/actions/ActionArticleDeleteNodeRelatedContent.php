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
                FROM {$this->config['dbTablePrefix']}article_node_rel
                WHERE
                   `id_node`={$data['id_node']}";
                   
        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {
            $this->model->action('article','deleteArticle',
                                 array('id_article' => (int)$row['id_article'],
                                       'id_node'    => (int)$data['id_node']));
        }        
                   
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_node_rel
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
