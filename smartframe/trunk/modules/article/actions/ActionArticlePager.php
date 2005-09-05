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
 * ActionArticlePager class 
 *
 * USAGE:
 *
 * $model->action('article','pager',
 *                array('id_node'    => int,      // id node
                        'result'     => & string, // result string ref. with pager links
                        'numItems'   => int,      // total items
                        'perPage'    => int,      // items per page
                        'numPage'    => int,      // current page
                        'delta'      => int,      // delta range number
                        'url'        => string,   // the url for each link
                        'var_prefix' => string,   // prefix for the pager link var
                        'css_class'  => string    // css class for the links 
                        ));
 *
 *
 *
 */
include_once(SMART_BASE_DIR . 'modules/common/includes/SmartPager.php');
 
class ActionArticlePager extends SmartAction
{
    /**
     * build pager links
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        // check pager variables
        if(!isset($data['perPage']) || ($data['perPage'] == 0))
        {
            $data['perPage'] = 10;
        }
        if(!isset($data['numPage']) || ($data['numPage'] == 0))
        {
            $data['numPage'] = 1;
        }      
        if(!isset($data['delta']))
        {
            $data['delta'] = 5;
        }  
        if(!isset($data['css_class']))
        {
            $data['css_class'] = 'smart_pager';
        }      
    
        if(isset($data['status']))
        {
            $sql_where = " AND a.`status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_where = "";
        }

        if(isset($data['id_node']))
        {
            $where = "r.`id_node`={$data['id_node']}
                      AND a.`id_article`=r.`id_article`";
            $table = "{$this->config['dbTablePrefix']}article_node_rel AS r";
        }
        elseif(isset($data['search']))
        {
            $search_string = $this->model->dba->escape( $data['search'] );
            $where = "MATCH (i.`text1`,i.`text2`,i.`text3`,i.`text4`)
                      AGAINST ('{$search_string}' IN BOOLEAN MODE) 
                      AND i.id_article=a.id_article ";
            $table = "{$this->config['dbTablePrefix']}article_index AS i";
        }        
        
        $sql = "SELECT SQL_CACHE
                    count(a.`id_article`) AS numArticles
                FROM 
                    {$this->config['dbTablePrefix']}article_article AS a,
                    {$table}
                WHERE
                   {$where}
                $sql_where";
                   
        $rs = $this->model->dba->query($sql);
        $row = $rs->fetchAssoc();    

        $config = array('numItems'   => (int)$row['numArticles'],
                        'perPage'    => (int)$data['perPage'],
                        'numPage'    => (int)$data['numPage'],
                        'delta'      => (int)$data['delta'],
                        'result'     => & $data['result'],
                        'url'        => (string)$data['url'],
                        'var_prefix' => (string)$data['var_prefix'],
                        'css_class'  => (string)$data['css_class']);        

        new SmartPager( $config );   
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(isset($data['id_node']))
        {
            if(!is_int($data['id_node']))
            {
                throw new SmartModelException('"id_node" isnt from type int');        
            }     
        }    

        if(isset($data['search']))
        {
            if(!is_string($data['search']))
            {
                throw new SmartModelException('"search" isnt from type string');        
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
       
        if(!isset($data['result']))
        {
            throw new SmartModelException('"result" isnt defined'); 
        }          
        if(!is_string($data['result']))
        {
            throw new SmartModelException('"result" isnt from type string'); 
        }    
        if(!isset($data['url']))
        {
            throw new SmartModelException('"url" isnt defined'); 
        }          
        if(!is_string($data['url']))
        {
            throw new SmartModelException('"url" isnt from type string'); 
        }   
        if(isset($data['var_prefix']))
        {
            if(!is_string($data['var_prefix']))
            {
                throw new SmartModelException('"var_prefix" isnt from type string'); 
            }  
        }          
       
        return TRUE;
    }
}

?>
