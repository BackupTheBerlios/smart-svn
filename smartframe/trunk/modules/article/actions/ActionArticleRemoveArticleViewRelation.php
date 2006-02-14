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
 
class ActionArticleRemoveArticleViewRelation extends SmartAction
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
            DELETE FROM
                {$this->config['dbTablePrefix']}article_view_rel
            WHERE
                `id_article`={$data['id_article']}";
        
        $rs = $this->model->dba->query($sql);   
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
        if(!is_int($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt from type int');        
        }   
        return TRUE;
    }    
}

?>
