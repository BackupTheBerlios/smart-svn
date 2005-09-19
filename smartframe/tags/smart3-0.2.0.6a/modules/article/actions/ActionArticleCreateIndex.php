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
 * ActionArticleCreateIndex class 
 *
 * USAGE:
 * $model->action('article','createIndex',
 *                array('id_article' => int))
 */
 
class ActionArticleCreateIndex extends SmartAction
{                                      
    /**
     * create article index
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT
                `overtitle`,`title`,
                `subtitle`,`header`,
                `description`,`body`,`ps`
            FROM
                {$this->config['dbTablePrefix']}article_article
            WHERE
                `id_article`={$data['id_article']}";
        
        $rs = $this->model->dba->query($sql);
        $field = $rs->fetchAssoc();
        
        $content = '';

        foreach($field as $f)
        {
            $content .= strip_tags($f).' ';
        }

        $substring = array();
   
        for($i = 0; $i <= 262140; $i = $i + 65535)
        {
            $substring[] = substr($this->model->dba->escape($content), $i, 65535);
        }
        
        $this->insert( $data['id_article'], $substring );   
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!is_int($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt from type int');        
        }

        return TRUE;
    }
    
    /**
     * insert data for indexing
     *
     * @param int $id_article
     * @param array $content
     */    
    public function insert( $id_article, & $content )
    { 
        $sql = "REPLACE DELAYED INTO {$this->config['dbTablePrefix']}article_index 
                   SET  `id_article`={$id_article},
                        `text1`='{$content[0]}',
                        `text2`='{$content[1]}',
                        `text3`='{$content[2]}',
                        `text4`='{$content[3]}'";

        $this->model->dba->query($sql);    
    }    
}

?>
