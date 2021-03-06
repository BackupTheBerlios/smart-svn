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
 * ActionArticleChangedateStatus class 
 *
 * USAGE:
 * $model->action('article','changedateStatus');
 *
 */
 
class ActionArticleChangedateStatus extends SmartAction
{
    /**
     * update article status
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $now = date("Y-m-d H:i:s", time() - $this->config['server_gmt'] * 3600);
        
        // get articles from where to change its status
        $sql = "
            SELECT
                `id_article`,`status`
            FROM
                {$this->config['dbTablePrefix']}article_changedate
            WHERE
                `changedate`<='{$now}'";
        
        $rs = $this->model->dba->query($sql);
        
        // no articles, return
        if($rs->numRows() == 0)
        {
            return;
        }
        
        $id_article = array();
        
        // update article status
        while($row = $rs->fetchAssoc())
        {
            $id_article[] = $row['id_article'];
            $sql = "
                UPDATE {$this->config['dbTablePrefix']}article_article
                    SET
                       `status`={$row['status']}
                    WHERE
                       `id_article`={$row['id_article']}";
        
            $this->model->dba->query($sql);      
        } 
        
        $in_article = implode(",",$id_article);
        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_changedate
                    WHERE
                       `id_article` IN({$in_article})";

        $this->model->dba->query($sql);        
    } 
}

?>
