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
 * ActionNavigationGetNodeStatus class 
 *
 * USAGE:
 * $model->action('navigation','checkStatusOfParentNodes',
 *                array('id_node' => int, 
 *                      'status'  => array('<|>|<=|>=|=', 1|2)))
 *
 */
 
class ActionNavigationGetNodeStatus extends SmartAction
{   
    /**
     * get node status
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT SQL_CACHE
                status
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_node`={$id_node}";
        
        $rs = $this->model->dba->query( $sql );  
        
        if( $row = $rs->fetchAssoc() )
        {
            return $row['status'];
        }
        return FALSE;
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt defined');        
        }
        if(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }
        return TRUE;
    }
}

?>
