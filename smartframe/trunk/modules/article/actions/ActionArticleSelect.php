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
 * ActionArticleSelect class 
 *
 * USAGE:
 * $model->action('article','select',
 *                array('result' => & array, 
 *                      'status' => array('<|>|<=|>=|=', 1|2),     // optional
 *                      'order'  => array('rank|title|
 *                                         articledate|pubdate|
 *                                         overtitle|subtitle', 'asc|desc'),// optional
 *                      'fields  => array('id_article','status','rank',
 *                                        'pubdate','articledate','modifydate',
 *                                        'lang','title','overtitle',
 *                                        'subtitle','header','description',
 *                                        'body','ps',
 *                                        'format','media_folder') ));
 *
 */
 
class ActionArticleSelect extends SmartAction
{
    /**
     * Allowed article fields and its type
     */
    protected $tblFields_article = array('id_article'   => 'Int',
                                         'id_node'      => 'Int',
                                         'status'       => 'Int',
                                         'rank'         => 'Int',
                                         'articledate'  => 'String',
                                         'pubdate'      => 'String',
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
            $_fields .= $comma.'a.`'.$f.'`';
            $comma = ',';
        }
        
        $sql_status  = "";  
        $sql_pubdate = ""; 
        $sql_modifydate = "";
        $sql_articledate = "";
        
        if(isset($data['status']))
        {
            $sql_status = " AND a.`status`{$data['status'][0]}{$data['status'][1]}";
        }

        if(isset($data['pubdate']))
        {
            $pubdate1 = $this->model->dba->escape($data['pubdate'][1]);
            $sql_pubdate = " AND a.`pubdate`{$data['pubdate'][0]}'{$pubdate1}'";
            if(isset($data['pubdate'][2]))
            {
                $pubdate3 = $this->model->dba->escape($data['pubdate'][3]);
                $sql_pubdate .= " AND a.`pubdate`{$data['pubdate'][2]}'{$pubdate3}'";            
            }
        }

        if(isset($data['modifydate']))
        {
            $modifydate1 = $this->model->dba->escape($data['modifydate'][1]);
            $sql_modifydate = " AND a.`modifydate`{$data['modifydate'][0]}'{$modifydate1}'";
            if(isset($data['modifydate'][2]))
            {
                $modifydate2 = $this->model->dba->escape($data['modifydate'][3]);
                $sql_modifydate .= " AND a.`modifydate`{$data['modifydate'][2]}{$modifydate2}";            
            }
        }

        if(isset($data['articledate']))
        {
            $articledate1 = $this->model->dba->escape($data['articledate'][1]);
            $sql_articledate = " AND a.`articledate`{$data['articledate'][0]}'{$articledate1}'";
            if(isset($data['articledate'][2]))
            {
                $articledate2 = $this->model->dba->escape($data['articledate'][3]);
                $sql_articledate .= " AND a.`articledate`{$data['articledate'][2]}{$articledate2}";            
            }
        }

        if(isset($data['order']))
        {
            $sql_order = " ORDER BY a.{$data['order'][0]} {$data['order'][1]}";
        }
        else
        {
            $sql_order = "ORDER BY a.title ASC";
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

        $search_string = $this->model->dba->escape( $data['search'] );
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}article_article
            WHERE 
                a.`id_article`=r.`id_article` 
                {$sql_pubdate}
                {$sql_modifydate}
                {$sql_articledate}
                {$sql_status}
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
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException('"result" isnt from type array'); 
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

        // check dates
        $dates = array('pubdate','modifydate','articledate');

        foreach($dates as $d)
        {
            if(isset($data[$d]))
            {
                if(!is_array($data[$d]))
                {
                    throw new SmartModelException("'$d' action array instruction isnt an array"); 
                }
                else
                {
                    if(isset($data[$d][0]) && isset($data[$d][1]))
                    {
                        if(!preg_match("/>|<|=|>=|<=|!=/",$data[$d][0]))
                        {
                            throw new SmartModelException("Wrong '$d' array[0] value: ".$data[$d][0]); 
                        }
                        if(!preg_match("/^[0-9]{4}/",$data[$d][1]))
                        {
                            throw new SmartModelException("Wrong '$d' array[1] value: ".$data[$d][1]); 
                        }                    
                    }
                    else
                    {
                        throw new SmartModelException("Wrong (missing) '$d' array values");
                    }
                    if(isset($data[$d][2]) && isset($data[$d][3]))
                    {
                        if(!preg_match("/>|<|=|>=|<=|!=/",$data[$d][2]))
                        {
                            throw new SmartModelException("Wrong '$d' array[2] value: ".$data[$d][2]); 
                        }
                        if(!preg_match("/^[0-9]{4}/",$data[$d][3]))
                        {
                            throw new SmartModelException("Wrong '$d' array[3] value: ".$data[$d][3]); 
                        }                    
                    }             
                }
            }
        }
        
        return TRUE;
    }
}

?>
