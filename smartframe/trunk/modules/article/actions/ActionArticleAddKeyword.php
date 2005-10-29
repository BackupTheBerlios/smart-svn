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



class ActionArticleAddKeyword extends SmartAction
{                                           
    /**
     * Add article
     *
     */
    public function perform( $data = FALSE )
    {     
        if($this->isKey( $data['id_article'], $data['id_key'] ) == 1)
        {
            return;
        }
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}article_key
                   (`id_key`,`id_article`)
                  VALUES
                   ({$data['id_key']},{$data['id_article']})";

        $this->model->dba->query($sql);                    
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
    /**
     * Add article
     *
     */
    private function isKey( $id_article, $id_key )
    {         
        $sql = "SELECT SQL_CACHE
                  `id_key`
                FROM 
                  {$this->config['dbTablePrefix']}article_key
                WHERE
                   `id_article`={$id_article}
                AND
                   `id_key`={$id_key}";

        $result = $this->model->dba->query($sql); 
        return $result->numRows();
    }     
}

?>