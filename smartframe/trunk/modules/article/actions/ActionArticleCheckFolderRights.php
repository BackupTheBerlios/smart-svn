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
 * ActionArticleCheckFolderRights
 *
 * USAGE:
 *
 * $model->action('article','checkFolderRights', array('error' => & array() )); 
 */
 
class ActionArticleCheckFolderRights extends SmartAction
{
    /**
     * check if folders are writeable by php scripts
     *
     */
    public function perform( $data = FALSE )
    {
        $article_folder = SMART_BASE_DIR . 'data/article';
        if(!is_writeable($article_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$article_folder;    
        }      

        return TRUE;
    } 
    /**
     * validate $data
     *
     */ 
    public function validate( $data = FALSE )
    {
        if(!isset($data['error']))
        {
            throw new SmartModelException("'error' isnt defined");
        }
        if(!is_array($data['error']))
        {
            throw new SmartModelException("'error' isnt from type array");
        }
        
        return TRUE;
    }
}

?>