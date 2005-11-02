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
 * ActionArticleRemoveKeyword class 
 *
 * remove id_article related id_key
 *
 * USAGE:
 *
 * $model->action('article','removeKeyword',
 *                array('id_article' => int,
 *                      'id_key'     => int));
 *
 */
 
class ActionArticleRemoveKeyword extends SmartAction
{
    private $sqlArticle = '';
    private $sqlKey     = '';
    
    /**
     * delete article related key
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {         
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_keyword
                  WHERE
                   {$this->sqlArticle}
                   {$this->sqlKey}";

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
        if(isset($data['id_article']))
        {
            if(!is_int($data['id_article']))
            {
                throw new SmartModelException('"id_article" isnt from type int');        
            }   
            $this->sqlArticle = "`id_article`={$data['id_article']}";
            $selcetedItem = TRUE;
        }    
        
        if(isset($data['id_key'])) 
        {
            if(!is_int($data['id_key']))
            {
                throw new SmartModelException("'id_key' isnt from type int");
            }  
            if(isset($selcetedItem))
            {
                $this->sqlKey = " AND ";
            }
            $this->sqlKey .= "`id_key`={$data['id_key']}";
            $selcetedItem  = TRUE;
        }

        if(!isset($selcetedItem))
        {
            throw new SmartModelException('Whether "id_key" nor "id_article" is defined');                      
        }
         
        return TRUE;
    }
}

?>
