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
 * ActionArticleDeleteExpired class 
 *
 * USAGE:
 * $model->action('article','deleteExpired');
 *
 */
 
class ActionArticleDeleteExpired extends SmartAction
{
    /**
     * delete article with status 0=delete which the last update is 
     * older than one day
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {        
        // get articles with status 'delete=0' and older than 1 day
        $sql = "
            SELECT
                `id_article`
            FROM
                {$this->config['dbTablePrefix']}article_article
            WHERE
                `status`=0
            AND
                `modifydate`<=NOW()-86400";
        
        $rs = $this->model->dba->query($sql);
        
        // no articles, return
        if($rs->numRows() == 0)
        {
            return;
        }       
        
        // delete expired articles
        while($row = $rs->fetchAssoc())
        {
            $this->model->action('article','deleteArticle',
                            array('id_article'  => (int)$row['id_article']));  
        }      
    } 
}

?>
