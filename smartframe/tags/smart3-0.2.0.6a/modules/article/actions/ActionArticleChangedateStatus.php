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
        // get articles from where to change its status
        $sql = "
            SELECT
                `id_article`,`status`
            FROM
                {$this->config['dbTablePrefix']}article_changedate
            WHERE
                `changedate`<=NOW()";
        
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
