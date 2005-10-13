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



class ActionArticleAddArticle extends SmartAction
{  
    /**
     * Allowed article fields and its type
     */
    protected $tblFields_article = array('status'       => 'Int',
                                         'rank'         => 'Int',
                                         'pubdate'      => 'String',
                                         'articledate'  => 'String',
                                         'lang'         => 'String',
                                         'title'        => 'String',
                                         'overtitle'    => 'String',
                                         'subtitle'     => 'String',
                                         'header'       => 'String',
                                         'description'  => 'String',
                                         'body'         => 'String',
                                         'ps'           => 'String',
                                         'fulltextbody' => 'String',
                                         'format'       => 'Int',
                                         'media_folder' => 'String');
                                         
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
         
        if(!isset($data['fields']['pubdate']))
        {
            $fields .= $comma."`pubdate`";
            $quest  .= $comma."NOW()";        
        }
        
        if(!isset($data['fields']['articledate']))
        {
            $fields .= $comma."`articledate`";
            $quest  .= $comma."NOW()";        
        }  
        
        if(!isset($data['fields']['rank']))
        {
            $fields .= $comma."`rank`";
            $quest  .= $comma.$this->getRank( $data['id_node'] );        
        }          

        $sql = "INSERT INTO {$this->config['dbTablePrefix']}article_article
                   ($fields,`id_node`)
                  VALUES
                   ({$quest},{$data['id_node']})";

        $this->model->dba->query($sql);                    
        
        $id_article = $this->model->dba->lastInsertID();

        // update article index
        $this->model->action('article','createIndex',
                              array('id_article' => (int)$id_article) );
        
        return $id_article;
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

        // error is required
        if(!isset($data['error']))
        {
            throw new SmartModelException("'error' array isnt defined");
        }
        elseif(!is_array($data['error']))
        {
            throw new SmartModelException("'error' isnt from type array");
        }  

        // title is required
        if(!isset($data['fields']['title']))
        {
            throw new SmartModelException("'title' isnt defined");
        }
        elseif(!is_string($data['fields']['title']))
        {
            throw new SmartModelException("'title' isnt from type string");
        }        
        elseif(empty($data['fields']['title']))
        {
            $data['error'][] = "'title' is empty";
            return FALSE;
        }        

        if(!isset($data['id_node'])) 
        {
            throw new SmartModelException("'id_node' isnt defined");
        }
        elseif(!is_int($data['id_node']))
        {
            throw new SmartModelException("'id_node' isnt from type int");
        }         
        elseif($data['id_node'] == 0)
        {
            throw new SmartModelException("id_node=0 isnt allowed");
        }            
        
        return TRUE;
    }  
    
    /**
     * get rank number for the new added article
     *
     * @param int $id_node Node ID
     */    
    private function getRank( $id_node )
    {
        $sql = "
            SELECT
                `rank`
            FROM
                {$this->config['dbTablePrefix']}article_article
            WHERE
                `id_node`={$id_node}
            ORDER BY `rank` DESC
            LIMIT 1";
        
        $rs = $this->model->dba->query($sql);
        $row = $rs->fetchAssoc();
        
        if(!isset($row['rank']))
        {
            return 0;
        }
        
        return ++$row['rank'];
    }        
}

?>