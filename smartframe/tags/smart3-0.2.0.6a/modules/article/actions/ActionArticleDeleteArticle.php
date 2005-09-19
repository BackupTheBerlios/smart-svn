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
 * ActionArticleDeleteArticle class 
 *
 * USAGE:
 *
 * $model->action('article','deleteArticle',
 *                array('id_article'  => int))
 *
 */
 
class ActionArticleDeleteArticle extends SmartAction
{
    /**
     * delete article and navigation node relation
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {         
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_lock
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_media_pic
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_media_file
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);
        
        $sql = "SELECT 
                  `media_folder`,
                  `id_node`
                FROM 
                  {$this->config['dbTablePrefix']}article_article
                WHERE
                   `id_article`={$data['id_article']}";
                   
        $rs = $this->model->dba->query($sql);

        $row = $rs->fetchAssoc();

        if(isset($row['media_folder']) && !empty($row['media_folder']))
        {
            // delete article data media folder
            SmartCommonUtil::deleteDirTree( SMART_BASE_DIR.'data/article/'.$row['media_folder'] );
        }
        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_article
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);
        
        // reorder node related article ranks
        $this->model->action('article','reorderRank',
                             array('id_node' => (int)$row['id_node']) );        
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
        
        return TRUE;
    }
}

?>
