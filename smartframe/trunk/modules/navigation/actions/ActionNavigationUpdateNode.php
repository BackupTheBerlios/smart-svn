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
 * ActionNavigationUpdateNode class 
 *
 */

include_once(SMART_BASE_DIR . 'modules/navigation/includes/ActionNavigation.php');
 
class ActionNavigationUpdateNode extends ActionNavigation
{
    /**
     * update navigation node
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        $comma = '';
        $fields = '';
        
        foreach($data['node'] as $key => $val)
        {
            $fields .= $comma.'`'.$key.'`=?';
            $comma = ',';
        }
        
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}navigation_node
                SET
                   $fields
                WHERE
                `id_node`={$data['id_node']}";
        
        $stmt = $this->model->dba->prepare($sql);                    
        
        foreach($data['node'] as $key => $val)
        {
            $methode = 'set'.$this->tblFields_node[$key];
            $stmt->$methode($val);
        }
       
        $stmt->execute();           
        
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
        // check if database fields exists
        foreach($data['node'] as $key => $val)
        {
            if(!isset($this->tblFields_node[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(preg_match("/[^0-9]/",$data['id_node']))
        {
            throw new SmartModelException('Wrong id_node format: '.$id_user);        
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
        }
        
        return TRUE;
    }
}

?>
