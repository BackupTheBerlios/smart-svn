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
 * ActionNavigationGetBranch class 
 *
 */

include_once(SMART_BASE_DIR . 'modules/navigation/includes/ActionNavigation.php');
 
class ActionNavigationGetBranch extends ActionNavigation
{
    /**
     * get navigation node branch
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
            $this->_fields .= $comma.'`'.$f.'`';
            $comma = ',';
        }       
        
        $this->getBranch( $data );
        
        // reverse array result
        $data['result'] = array_reverse($data['result']);
        
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
        
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_node[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['id_node']))
        {
            throw new SmartModelException('"id_node" action array instruction is required'); 
        }
        
        if(preg_match("/[^0-9]/",$data['id_node']))
        {
            throw new SmartModelException('Wrong id_node format: '.$id_user);        
        }

        if(!isset($data['result']) || !is_array($data['result']))
        {
            throw new SmartModelException('Missing "result" array var or "result isnt defined as an array.'); 
        }
        return TRUE;
    }
    
    /**
     * walk recursive until the top node
     *
     * @param array $data
     */     
    private function getBranch( &$data )
    {
        $sql = "
            SELECT
                {$this->_fields}
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_parent`={$data['id_node']}";
        
        $rs = $this->model->dba->query($sql);

        if($row = $rs->fetchAssoc())
        {
            $tmp = array();
            foreach ($data['fields'] as $f)
            {
                $tmp[$f] = stripslashes($row[$f]);
            }  
            $data['result'][] = $tmp;
            $data['id_node'] = $row['id_node'];
            $this->getBranch($data);
        }    
    }
}

?>
