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
 * ActionNavigationGetTree class 
 *
 */

include_once(SMART_BASE_DIR . 'modules/navigation/includes/ActionNavigation.php');
 
class ActionNavigationGetTree extends ActionNavigation
{
    /**
     * get navigation node (sub)tree from a given id_node
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    { 
        $this->result_tree = &$data['result'];
        $this->result_tree = array();
        $this->selectTree( $data );
        
        $this->tree( $data['id_node'] );
  
        return TRUE;
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
            if(!isset($this->tblFields_node[$val]))
            {
                throw new SmartModelException("Field '".$val."' dosent exists!");
            }
        }

        if(preg_match("/[^0-9]/",$data['id_node']))
        {
            throw new SmartModelException('Wrong id_node format: '.$id_user);        
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
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}navigation_node 
            ORDER BY `rank`";
        
        $rs = $this->model->dba->query($sql);
        
        $this->node_tree = array();

        while($row = $rs->fetchAssoc())
        {
            $tmp = array();
            foreach ($data['fields'] as $f)
            {
                $tmp[$f] = stripslashes($row[$f]);
            }  
            $this->node_tree[] = $tmp;
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
                $this->tree($tmp['id_node'], $level+1);
            }
        }
        else
        {
            return 0;
        }
    }     
}

?>
