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
 * User lock action class 
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
                $this->lastAccess($data);
                return;
            case 'last_x':
                $this->unlockUser($data);
                return;
            case 'all':
                $this->allAccesses($data);   
                return;
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
        if(isset($data['id_user']) && @preg_match("/[^0-9]/", $data['id_user']) )
        {
            throw new SmartModelException('Wrong id_user format: '.$data['id_user']);         
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
     * Unlock a user
     *
     * @param array $data User data
     */    
    private function unlockUser($data)
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_lock
                  WHERE
                   `id_user`={$data['id_user']}";

        $this->model->db->executeUpdate($sql);         
    }   
    
    /**
     * Delete all locks which are older than 1 hour
     *
     */    
    private function deleteTimeExceedingLocks()
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_lock
                  WHERE
                   `lock_time` < NOW()-3600";

        $this->model->db->executeUpdate($sql);         
    }  
    
    /**
     * Unlock all entries
     *
     */    
    private function unlockAll()
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_lock";

        $this->model->db->executeUpdate($sql);         
    }      

    /**
     * Unlock all entries which a user has locked
     *
     */    
    private function unlockByIdUser($data)
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_lock
                WHERE `id_user`={$data['id_user']}";

        $this->model->db->executeUpdate($sql);         
    }
    
    /**
     * Check if a user is locked and if yes by which id_user
     *
     * @param array $data User data
     * @param mixed FALSE if not locked True if locked by the logged user
     *              else id_user of the user who locks
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