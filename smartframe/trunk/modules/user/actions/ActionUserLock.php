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


class ActionUserLock extends SmartAction
{
    /**
     * User lock actions
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    { 
        $this->deleteTimeExceedingLocks();
        
        switch($data['job'])
        {
            case 'lock':
                return $this->lockUser($data);
            case 'unlock':
                $this->unlockUser($data);
                return;
            case 'is_locked':
                return $this->isUserLocked($data);
            case 'unlock_all':
                $this->unlockAll(); 
                return;
            case 'unlock_from_user':
                $this->unlockByIdUser($data); 
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
        if( @preg_match("/[^0-9]/", $data['id_user']) )
        {
            throw new SmartModelException('Wrong id_user format: '.$data['id_user']);         
        } 
        
        // Check if id_user exists
        if($this->userExists($data['id_user']) == FALSE)
        {
            throw new SmartModelException('id_user dosent exists: '.$data['id_user']); 
        }    
        
        return TRUE;
    }
    
    /**
     * check if id_user exists
     *
     * @param int $id_user User id
     * @return bool
     */    
    function userExists( $id_user )
    {
        
        $sql = "
            SELECT
                id_user
            FROM
                {$this->config['dbTablePrefix']}user_user
            WHERE
                id_user='$id_user'";
        
        $result = $this->model->db->executeQuery($sql);

        if($result->getRecordCount() > 0)
        {
            return TRUE;
        }
        
        return FALSE;    
    } 

    /**
     * Lock a user for modifying
     *
     * @param array $data User data
     */    
    private function lockUser($data)
    {
        $result = $this->isUserLocked($data);
        // False = the user isnt locked
        // True = the user is locked by the logged user
        // if not locked by the logged user $result
        // contnains the id of the user which locks
        if(($result != FALSE) && ($result != TRUE))
        {
            return $result;
        }
        
        if($result == FALSE)
        {
            $sql = "INSERT INTO {$this->config['dbTablePrefix']}user_lock
                        (`id_user`,`lock_time`,`by_id_user`)
                    VALUES
                       ({$data['id_user']},NOW(),{$data['by_id_user']})";

            $this->model->db->executeUpdate($sql); 
        }
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
    private function isUserLocked($data)
    {
        $sql = "SELECT 
                    `by_id_user` 
                FROM 
                    {$this->config['dbTablePrefix']}user_lock
                WHERE
                   `id_user`={$data['id_user']}";

        $result = $this->model->db->executeQuery($sql);  
       
        if($result->getRecordCount() == 1)
        {            
            $result->first();
            $row = $result->getRow();
            
            if($data['by_id_user'] == $row['by_id_user'])
            {
                return TRUE;
            }
            
            return $row['by_id_user'];
        }
        
        return FALSE;
    }     
}

?>