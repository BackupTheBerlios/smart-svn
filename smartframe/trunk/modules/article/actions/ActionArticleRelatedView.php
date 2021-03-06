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
 * ActionArticleRelatedView class 
 *
 * USAGE:
 * $model->action( 'article', 'relatedView',
 *                 array('id_article' => int,
 *                       'result'     => & string));
 *
 *
 */
 
class ActionArticleRelatedView extends SmartAction
{
    /**
     * get article related view
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {  
        // First check if there is a view assigned to this specific article
        if($this->config['article']['use_article_view'] == 1)
        {
            if($this->getArticleView( $data ) != FALSE )
            {
                return;
            }
        }

        // then check if there is a view assigned to the article node       
        $sql = "
            SELECT
                v.`name`
            FROM
                {$this->config['dbTablePrefix']}article_article AS aa,
                {$this->config['dbTablePrefix']}article_node_view_rel AS an,
                {$this->config['dbTablePrefix']}article_view AS v
            WHERE
                aa.`id_article`={$data['id_article']} 
            AND
                aa.`id_node`=an.`id_node`
            AND
                an.`id_view`=v.`id_view`";

        $rs = $this->model->dba->query($sql);
       
        if( $row = $rs->fetchAssoc() )
        {
            $data['result'] = $row['name'];
        }
        else
        {
            $data['result'] = '';
        }
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_article']))
        {
            return FALSE;
        }

        if(!is_int($data['id_article']))
        {
            return FALSE;
        }
        
        return TRUE;
    }
    /**
     * get article related view
     *
     * @param array $data
     */
    private function getArticleView( & $data )
    {   
        $sql = "
            SELECT
                v.`name`
            FROM
                {$this->config['dbTablePrefix']}article_view_rel AS an,
                {$this->config['dbTablePrefix']}article_view AS v
            WHERE
                an.`id_article`={$data['id_article']} 
            AND
                an.`id_view`=v.`id_view`";

        $rs = $this->model->dba->query($sql);
       
        if( $row = $rs->fetchAssoc() )
        {
            $data['result'] = $row['name'];
            return TRUE;
        }
        else
        {
            $data['result'] = '';
            return FALSE;
        }
    }     
}

?>
