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
 * ActionArticleOptimizeDbTables class 
 *
 * USAGE:
 * $model->action('article','optimizeDbTables')
 */
 
class ActionArticleOptimizeDbTables extends SmartAction
{                                      
    /**
     * optimize article module DB tables
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "OPTIMIZE NO_WRITE_TO_BINLOG TABLE 
                  {$this->config['dbTablePrefix']}article_article,
                  {$this->config['dbTablePrefix']}article_changedate,
                  {$this->config['dbTablePrefix']}article_index,
                  {$this->config['dbTablePrefix']}article_lock,
                  {$this->config['dbTablePrefix']}article_node_rel,
                  {$this->config['dbTablePrefix']}article_media_pic,
                  {$this->config['dbTablePrefix']}article_media_file";
        
        $this->model->dba->query($sql);
    } 
}

?>
