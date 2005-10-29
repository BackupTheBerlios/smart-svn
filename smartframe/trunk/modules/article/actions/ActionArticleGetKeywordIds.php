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
 * ActionArticleAddArticle
 *
 * USAGE:
 * 
 */

class ActionArticleGetKeywordIds extends SmartAction
{                                           
    /**
     * Add article
     *
     */
    public function perform( $data = FALSE )
    {     
        $sql = "SELECT SQL_CACHE
                  `id_key`
                FROM 
                  {$this->config['dbTablePrefix']}article_key
                WHERE
                   `id_article`={$data['id_article']}";

        $result = $this->model->dba->query($sql);  
        while($row = $result->fetchAssoc())
        {
            $data['result'][] = $row['id_key'];
        }         
    } 
    
    /**
     * validate array data
     *
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['id_article'])) 
        {
            throw new SmartModelException("'id_article' isnt defined");
        }
        elseif(!is_int($data['id_article']))
        {
            throw new SmartModelException("'id_article' isnt from type int");
        }         
          
        return TRUE;
    }  
}

?>