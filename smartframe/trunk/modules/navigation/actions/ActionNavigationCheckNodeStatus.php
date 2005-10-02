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
 * ActionNavigationGetNode class 
 *
 * USAGE:
 * $model->action('navigation','checkStatusOfParentNodes',
 *                array('id_node' => int, 
 *                      'status'  => array('<|>|<=|>=|=', 1|2)))
 *
 */
 
class ActionNavigationCheckNodeStatus extends SmartAction
{
    /**
     * Status check flag of the parent nodes
     * FALSE if one of the parent nodes has a lower
     * status value than the demanded node
     * @var bool $parentStatus
     */
    protected $parentStatus;
    
    /**
     * Check status
     *
     * @param array $data
     * @return bool true or false
     */
    function perform( $data = FALSE )
    {
        $this->parentStatus = TRUE;
        $this->status = array($data['status'][0],$data['status'][1]);
        $this->checkParentNodeStatus( $data['id_node'] );  
        return $this->parentStatus;
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
     * Check status of the parent nodes
     * Set $this->parentStatus to FALSE  if one of the parent nodes has a lower
     * status value than the demanded node
     * @param int $id_node
     */    
    private function checkParentNodeStatus( $id_node )
    {     
        $sql = "
            SELECT SQL_CACHE
                id_parent
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_node`={$id_node} 
            AND
                `status`{$this->status[0]}{$this->status[1]}";
        
        $rs = $this->model->dba->query( $sql );  
        
        if( $row = $rs->fetchAssoc() )
        {
            if( $row['id_parent'] != 0 )
            {
                $this->checkParentNodeStatus( $row['id_parent'] );
            }
            return;
        }
        
        $this->parentStatus = FALSE; 
        return;
    }
}

?>
