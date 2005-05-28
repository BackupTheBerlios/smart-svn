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
 * ActionUserAccess class 
 *
 */

/**
 * USE:
 *
 * ** Update/Insert time of a given user **
 * $model->action('user','access',
 *                array('job'    => (string)'update',
 *                      'id_user => (int)user ID ));
 *
 * ** Get last access time of a given user **
 *
 * $model->action('user','access',
 *                array('job'    => (string)'last',
 *                      'id_user => (int)user ID )); 
 *
 * Return: 1. FALSE if no user 
 *         2. Time (0000-00-00 00:00:00)
 * 
 * ** Get last x access times **
 *
 * $model->action('user','access',
 *                array('job' => (string)'last_x',
 *                      'num' => (int)number of last accesses));  
 *
 * Return: 1. array[]['access']
 *                   ['id_user']
 *
 * ** Get all access times **
 *
 * $model->action('user','access',
 *                array('job' => (string)'all'));  
 *
 * Return: 1. array[]['access']
 *                   ['id_user']
 *
 * ** Remove a user from the last access list **
 *
 * $model->action('user','access',
 *                array('job'     => (string)'delete',
 *                      'id_user' => (int)user ID));  
 *
 *
 */
class ActionUserAccess extends SmartAction
{
    /**
     * User lock actions
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {   
        switch($data['job'])
        {
            case 'update':
                $this->updateAccess($data);
                return;        
            case 'last':
                return $this->lastAccess($data);
            case 'last_x':
                return $this->lastXAccesses($data);
            case 'all':
                return $this->allAccesses($data);   
            case 'delete':
                $this->deleteAccess($data);   
                return;                
            default:
                throw new SmartModelException('Action not available: '.$data['action']); 
        }
        
        return TRUE;
    }
    
    /**
     * validate user data
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {       
        if(!isset($data['job']) || empty($data['job']))
        {
            throw new SmartModelException('No [job] defined for this action'); 
        }
        
        switch($data['job'])
        {
            case 'update':
                // id_user must exists to update user last access
                if(!isset($data['id_user']))
                {
                    return FALSE;         
                } 
                elseif(@preg_match("/[^0-9]/", $data['id_user']))
                {
                    throw new SmartModelException('[id_user] has wrong format'); 
                }
                break;  
            case 'last':
            case 'delete':
                if(!isset($data['id_user']))
                {
                    throw new SmartModelException('[id_user] isnt set');         
                } 
                elseif(@preg_match("/[^0-9]/", $data['id_user']))
                {
                    throw new SmartModelException('[id_user] has wrong format'); 
                }  
                break;
            case 'last_x':
                // id_user must exists to update user last access
                if(!isset($data['num']) || @preg_match("/[^0-9]/", $data['num']) )
                {
                    throw new SmartModelException('[num] isnt set or has wrong format');       
                } 
                break;                
            default:
                throw new SmartModelException('[job] not available: '.$data['job']); 
        }        
               
        return TRUE;
    }
    

    /**
     * Update user access time
     *
     * @param array $data User data
     */    
    private function updateAccess($data)
    {
        $sql = "REPLACE INTO {$this->config['dbTablePrefix']}user_access
                    SET
                       `id_user`={$data['id_user']},
                       `access`=NOW()";

        $this->model->db->executeUpdate($sql); 

        return TRUE;
    }
    
    /**
     * Get last access time of a given id_user
     *
     * @param array $data User data
     * @return array
     */    
    private function lastXAccesses($data)
    {
        $sql = "SELECT 
                    `access`,
                    `id_user`
                FROM 
                    {$this->config['dbTablePrefix']}user_access
                LIMIT
                    {$data['num']}
                ORDER BY
                    `access` DESC";

        $result = $this->model->db->executeQuery($sql);  
       
        $res = array();
       
        $result->first();
        while($row = $result->getRow())
        {
            $res[] =  array('access'  => $row['access'],
                            'id_user' => $row['id_user']);
        }
        
        return $res;
    }      
    /**
     * Get all accesses
     *
     * @param array $data User data
     * @return array 
     */    
    private function allAccesses($data)
    {
        $sql = "SELECT 
                    `access`,
                    `id_user`
                FROM 
                    {$this->config['dbTablePrefix']}user_access
                ORDER BY
                    `access` DESC";

        $result = $this->model->db->executeQuery($sql);  
       
        $res = array();
       
        $result->first();
        while($row = $result->getRow())
        {
            $res[] =  array('access'  => $row['access'],
                            'id_user' => $row['id_user']);
        }
        
        return $res;
    }  
    /**
     * Delete access time of a given id_user
     *
     */    
    private function deleteAccess($data)
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_access
                WHERE `id_user`={$data['id_user']}";

        $this->model->db->executeUpdate($sql);         
    }
    
    /**
     * Get last access time of a given id_user
     *
     * @param array $data User data
     * @param mixed FALSE if no last access time
     *              else time of last access
     */    
    private function lastAccess($data)
    {
        $sql = "SELECT 
                    `access` 
                FROM 
                    {$this->config['dbTablePrefix']}user_access
                WHERE
                   `id_user`={$data['id_user']}";

        $result = $this->model->db->executeQuery($sql);  
       
        if($result->getRecordCount() == 1)
        {            
            $result->first();
            $row = $result->getRow();
            
            return $row['access'];
        }
        
        return FALSE;
    }     
}

?>