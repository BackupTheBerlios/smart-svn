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
 * ActionKeywordGetTree class 
 *
 * USAGE:
 * $model->action('keyword','getTree',
 *                array('id_key' => int,      // top level node
 *                      'result'  => & array, 
 *                      'status'  => array('<|>|<=|>=|=', 1|2),     // optional
 *                      'fields'  => array('id_key','status','rank'
 *                                         'format','media_folder','id_parent','id_sector',
 *                                         'title','short_text',
 *                                         'body','id_view','logo')))
 *
 */

class ActionKeywordGetTree extends SmartAction
{
    /**
     * Fields and the format of each of the db table keyword 
     *
     */
    private $tblFields_keyword = 
                      array('id_key'      => 'Int',
                            'id_parent'   => 'Int',
                            'status'      => 'Int',
                            'title'       => 'String',
                            'description' => 'String');
                            
    /**
     * get navigation node (sub)tree from a given id_key
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    { 
        $this->result_tree = &$data['result'];
        $this->selectTree( $data );
        
        $this->tree( $data['id_key'] );
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        foreach($data['fields'] as $val)
        {
            if(!isset($this->tblFields_keyword[$val]))
            {
                throw new SmartModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('"result" isnt defined');        
        }
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException('"result" isnt from type array');        
        }

        if(!isset($data['id_key']))
        {
            throw new SmartModelException('"id_key" isnt defined');        
        }
        elseif(!is_int($data['id_key']))
        {
            throw new SmartModelException('"id_key" isnt from type int');        
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
    /**
     * load the whole navigation node tree in an array
     *
     * @param array $data
     */    
    function selectTree( $data )
    {         
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.'`'.$f.'`';
            $comma = ',';
        }

        // id_parent is required for internal use
        if(!in_array('id_parent',$data['fields']))
        {
            array_push($data['fields'],'id_parent');
        }
        // status is required for internal use
        if(!in_array('status',$data['fields']))
        {
            array_push($data['fields'],'status');
        }

        if(isset($data['status']))
        {
            $sql_where = " WHERE status{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_where = "";
        }
        
        $sql = "
            SELECT SQL_CACHE
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}keyword 
            {$sql_where}
            ORDER BY `title`";
        
        $rs = $this->model->dba->query($sql);
        
        $this->node_tree = array();

        while($row = $rs->fetchAssoc())
        {
            $this->node_tree[] = $row;
        }
    }
    /**
     * get the demanded navigation node (sub)tree of a given id_parent
     *
     * @param int $id_parent Node from which we need the (sub)tree
     * @param int $level indent level (for function internal use)
     */       
    private function tree($id_parent = 0, $level = 0)
    {
        $tt = array();
        $x = 0;
        $loop = 0;

        foreach($this->node_tree as $n)
        {         
            if( $n['id_parent'] == $id_parent)
            {
                $tt[$x++] = $loop;  
            }  
            $loop++;
        }

        /*-- if there are some parent ids --*/
        if( $x != 0){             
            foreach($tt as $d)
            {
                $tmp = array();

                foreach($this->node_tree[$d] as $node => $value)
                {                
                    $tmp[$node] = $value; 
                }
                $tmp['level']  = $level;
                $tmp['status'] = $this->node_tree[$d]['status'];
                
                $this->result_tree[] = $tmp;
                $this->tree($tmp['id_key'], $level+1);
            }
        }
        else
        {
            return 0;
        }
    }     
}

?>
