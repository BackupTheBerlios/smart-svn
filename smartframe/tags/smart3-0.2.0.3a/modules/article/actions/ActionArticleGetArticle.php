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
 * ActionArticleGetArticle class 
 *
 * USAGE:
 * $model->action('article','getArticle',
 *                array('id_article' => int, 
 *                      'result'     => & array, 
 *                      'status'     => array('<|>|<=|>=|=', 1|2),     // optional
 *                      'fields      => array('id_article','status','rank',
 *                                            'activedate','inactivedate','pubdate',
 *                                            'lang','title','overtitle',
 *                                            'subtitle','header','description',
 *                                            'body','ps','fulltextbody',
 *                                            'format','media_folder') ));
 *
 */
 
class ActionArticleGetArticle extends SmartAction
{
    /**
     * Allowed article fields and its type
     */
    protected $tblFields_article = array('id_article'   => 'Int',
                                         'status'       => 'Int',
                                         'rank'         => 'Int',
                                         'articledate'  => 'String',
                                         'pubdate'      => 'String',
                                         'changedate'   => 'String',
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
                                         'logo'         => 'String',
                                         'media_folder' => 'String');
    /**
     * get article data
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            if($f == 'changedate')
            {
                continue;
            }
            $_fields .= $comma.'`'.$f.'`';
            $comma = ',';
        }
        
        
        
        if(isset($data['status']))
        {
            $sql_where = " AND `status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_where = "";
        }
        
        $sql = "
            SELECT SQL_CACHE
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}article_article
            WHERE
                `id_article`={$data['id_article']} 
                {$sql_where}";
        
        $rs = $this->model->dba->query($sql);
        
        if($rs->numRows() == 0)
        {
            throw new SmartModelException('No article with id: '.$data['id_article']);
        }
        
        $data['result'] = $rs->fetchAssoc();
       
        if(in_array('changedate',$data['fields']))
        {
            $sql = "
                SELECT
                    `changedate`,`status` AS `changestatus`
                FROM
                    {$this->config['dbTablePrefix']}article_changedate
                WHERE
                    `id_article`={$data['id_article']}";
        
            $rs = $this->model->dba->query($sql);
            
            if( $rs->numRows() > 0 )
            {
                $row = $rs->fetchAssoc();
                $data['result']['changedate']   = $row['changedate'];
                $data['result']['changestatus'] = $row['changestatus'];
                
            }
        }        
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        foreach($data['fields'] as $val)
        {
            if(!isset($this->tblFields_article[$val]))
            {
                throw new SmartModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt defined');        
        }
        elseif(!is_int($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt from type int');        
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
        }
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException('"result" isnt from type array'); 
        }        

        if(isset($data['status']))
        {
            if(!is_array($data['status']))
            {
                throw new SmartModelException('"status" isnt an array'); 
            }
            else
            {
                if(!isset($data['status'][0]) || !preg_match("/>|<|=|>=|<=|!=/",$data['status'][0]))
                {
                    throw new SmartModelException('Wrong "status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['status'][1]) || !is_int($data['status'][1]))
                {
                    throw new SmartModelException('Wrong "status" array[1] value: '.$data['status'][1]); 
                }
            }
        }
        
        return TRUE;
    }
}

?>
