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
 * ActionArticleGetNodeArticles class 
 * USAGE:
 *
 * $model->action('article','getArticles',
 *                array('id_node' => int,
 *                      'result'  => & array,
 *                      'status'  => array('>|<|=|>=|<=|!=',1|2),            // optional
 *                      'order'   => array('rank|title|
 *                                          articledate|pubdate|
 *                                          overtitle|subtitle', 'asc|desc'),// optional
 *                      'fields   => array('id_node','id_article','status','rank',
 *                                         'activedate','inactivedate','pubdate',
 *                                         'lang','title','overtitle',
 *                                         'subtitle','header','description',
 *                                         'body','ps','fulltextbody',
 *                                         'format','media_folder') ));
 *
 */


class ActionArticleGetArticles extends SmartAction
{
    /**
     * Allowed sql caching
     */
    protected $sqlCache = 'SQL_CACHE';
    
    /**
     * Allowed article fields and its type
     */
    protected $tblFields_article = array('id_article'   => 'Int',
                                         'id_node'      => 'Int',
                                         'status'       => 'Int',
                                         'rank'         => 'Int',
                                         'pubdate'      => 'String',
                                         'articledate'  => 'String',
                                         'modifydate'   => 'String',
                                         'lang'         => 'String',
                                         'title'        => 'String',
                                         'overtitle'    => 'String',
                                         'subtitle'     => 'String',
                                         'header'       => 'String',
                                         'description'  => 'String',
                                         'body'         => 'String',
                                         'ps'           => 'String',
                                         'format'       => 'Int',
                                         'logo'         => 'String',
                                         'media_folder' => 'String');

    /**
     * get articles data of a given id_node
     *
     * @param array $data
     * @return bool true or false on error
     */
    public function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.'`'.$f.'`';
            $comma = ',';
        }
        
        if(isset($data['status']))
        {
            $sql_where = "`status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_where = "`status`>=4";
        }
        
        if(isset($data['pubdate']))
        {
            $sql_pubdate = " AND `pubdate`{$data['pubdate'][0]}{$data['pubdate'][1]}()";
        }
        else
        {
            $sql_pubdate = "";
        }  

        if(isset($data['modifydate']))
        {
            $sql_modifydate = " AND `modifydate`{$data['modifydate'][0]}{$data['modifydate'][1]}()";
        }
        else
        {
            $sql_modifydate = "";
        }  
        
        if(isset($data['order']))
        {
            $sql_order = " ORDER BY {$data['order'][0]} {$data['order'][1]}";
        }
        else
        {
            $sql_order = "ORDER BY title ASC";
        }        

        if(isset($data['limit']))
        { 
            if( $data['limit']['numPage'] < 1 )
            {
                $data['limit']['numPage'] = 1;
            }        
            $numPage = ($data['limit']['numPage'] - 1) * $data['limit']['perPage'];
            $sql_limit = " LIMIT {$numPage},{$data['limit']['perPage']}";
        }
        else
        {
            $sql_limit = "";
        }   
        
        $sql = "
            SELECT {$this->sqlCache}
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}article_article
            WHERE
                {$sql_where} 
                {$sql_pubdate}
                {$sql_modifydate}
                {$sql_order}
                {$sql_limit}";

        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {
            $data['result'][] = $row;
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

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
        }

        if(isset($data['limit']))
        {        
            if(!isset($data['limit']['numPage']))
            {
                throw new SmartModelException('numPage" isnt defined'); 
            } 
            if(!is_int($data['limit']['numPage']))
            {
                throw new SmartModelException('numPage" isnt from type int'); 
            }             
            if(!isset($data['limit']['perPage']))
            {
                throw new SmartModelException('"perPage" isnt defined'); 
            } 
            if(!is_int($data['limit']['perPage']))
            {
                throw new SmartModelException('"perPage" isnt from type int'); 
            }  
            elseif( $data['limit']['perPage'] < 2 )
            {
                throw new SmartModelException('"perPage" must be >= 2');
            }
        }
        
        if(isset($data['status']))
        {
            if(!is_array($data['status']))
            {
                throw new SmartModelException('"status" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['status'][0]))
                {
                    throw new SmartModelException('Wrong "status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['status'][1]) || preg_match("/[^0-9]+/",$data['status'][1]))
                {
                    throw new SmartModelException('Wrong "status" array[1] value: '.$data['status'][1]); 
                }
            }
        }

        if(isset($data['order']))
        {
            if(!is_array($data['order']))
            {
                throw new SmartModelException('"order" action array instruction isnt an array'); 
            }
            else
            {
                if(!isset($this->tblFields_article[$data['order'][0]]))
                {
                    throw new SmartModelException('Wrong "order" array[0] value: '.$data['order'][0]); 
                }

                if(isset($data['order'][1]))
                {
                    if(!preg_match("/asc|desc/i",$data['order'][1]))
                    {
                        throw new SmartModelException('Wrong "order" array[1] value: '.$data['order'][1]); 
                    }
                }
                else
                {
                    $data['order'][1] = 'ASC';
                }
            }
        }

        if(isset($data['disable_sql_cache']))
        {
            if(!preg_match("/^SQL_NO_CACHE$/",$data['disable_sql_cache']))
            {
                throw new SmartModelException('Wrong "disable_sql_cache" string value: '.$data['disable_sql_cache']); 
            }
            $this->sqlCache = 'SQL_NO_CACHE';
        }
        
        if(isset($data['pubdate']))
        {
            if(!is_array($data['pubdate']))
            {
                throw new SmartModelException('"pubdate" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['pubdate'][0]))
                {
                    throw new SmartModelException('Wrong "pubdate" array[0] value: '.$data['pubdate'][0]); 
                }

                if(!isset($data['pubdate'][1]) || !preg_match("/^CURRENT_TIMESTAMP$/i",$data['pubdate'][1]))
                {
                    throw new SmartModelException('Wrong "pubdate" array[1] value: '.$data['pubdate'][1]); 
                }
            }
            $this->sqlCache = 'SQL_NO_CACHE';
        }

        if(isset($data['modifydate']))
        {
            if(!is_array($data['modifydate']))
            {
                throw new SmartModelException('"modifydate" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['modifydate'][0]))
                {
                    throw new SmartModelException('Wrong "modifydate" array[0] value: '.$data['modifydate'][0]); 
                }

                if(!isset($data['modifydate'][1]) || !preg_match("/^CURRENT_TIMESTAMP$/i",$data['modifydate'][1]))
                {
                    throw new SmartModelException('Wrong "modifydate" array[1] value: '.$data['modifydate'][1]); 
                }
            }
            $this->sqlCache = 'SQL_NO_CACHE';
        }
        
        return TRUE;
    }    
}

?>
