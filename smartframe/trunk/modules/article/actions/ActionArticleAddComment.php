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
 * $model->action('article','addArticle',
 *                array('id_node' => int,                              // required
 *                      'error'   => & array,
 *                      'fields'  => array('status'       => 'Int',
                                           'rank'         => 'Int',
                                           'activedate'   => 'String',
                                           'inactivedate' => 'String',
                                           'pubdate'      => 'String',
                                           'lang'         => 'String',
                                           'title'        => 'String', // title required
                                           'overtitle'    => 'String',
                                           'subtitle'     => 'String',
                                           'header'       => 'String',
                                           'description'  => 'String',
                                           'body'         => 'String',
                                           'ps'           => 'String',
                                           'fulltextbody' => 'String',
                                           'format'       => 'Int',
                                           'media_folder' => 'String')));
 *
 *
 * return new id_article (int)
 */



class ActionArticleAddComment extends SmartAction
{  
    /**
     * Allowed article fields and its type
     */
    protected $tblFields = array('id_article' => 'Int',
                                 'id_user'    => 'Int',
                                 'body'       => 'String',
                                 'author'     => 'String',
                                 'email'      => 'String');
                                         
    /**
     * Add article
     *
     */
    public function perform( $data = FALSE )
    {
        $comma  = "";
        $fields = "";
        $quest  = "";
        
        foreach($data['fields'] as $key => $val)
        {
            $fields .= $comma."`".$key."`";
            $quest  .= $comma."'".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }        
         
        $fields .= $comma."`pubdate`";
        $quest  .= $comma."NOW()";    
        
        $fields .= $comma."`ip`";
        $quest  .= $comma."'{$_SERVER["REMOTE_ADDR"]}'";            

        $fields .= $comma."`agent`";
        $quest  .= $comma."'{$_SERVER["HTTP_USER_AGENT"]}'";  

        $fields .= $comma."`status`";
        $quest  .= $comma."'{$this->model->config['article']['validate_comment']}'";  

        $sql = "INSERT INTO {$this->config['dbTablePrefix']}article_comment
                   ($fields)
                  VALUES
                   ({$quest})";

        $this->model->dba->query($sql);
    } 
    
    /**
     * validate array data
     *
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_article[$key]))
            {
                throw new SmartModelException("Field '".$key."' isnt allowed!");
            }
        }

        // title is required
        if(!isset($data['fields']['body']))
        {
            throw new SmartModelException("'body' isnt defined");
        }
        elseif(!is_string($data['fields']['body']))
        {
            throw new SmartModelException("'title' isnt from type string");
        }              

        if(!isset($data['fields']['id_article'])) 
        {
            throw new SmartModelException("'id_article' isnt defined");
        }
        elseif(!is_int($data['fields']['id_article']))
        {
            throw new SmartModelException("'id_article' isnt from type int");
        }                    
        
        return TRUE;
    }  
}

?>