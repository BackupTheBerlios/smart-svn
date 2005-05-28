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
 * ActionUserLock class 
 *
 */

/**
 * USE:
 *
 * ** Lock a given user by an other user**
 *
 * $model->action('user','lock',
 *                array('job'    => (string)'lock',
 *                      'id_user => (int)user ID to lock,
 *                      'by_id_user' => (int)user ID that locks));
 *
 * Return: 1) TRUE if a user was successfull locked
 *         2) user ID, which locked the user in an other session
 *
 * ** Unlock a given user **
 *
 * $model->action('user','lock',
 *                array('job'    => (string)'unlock',
 *                      'id_user => (int)locked user ID )); 
 *
 * 
 * ** Is a user locked ? **
 *
 * $model->action('user','lock',
 *                array('job'    => (string)'is_locked',
 *                      'id_user => (int)user ID ));  
 *
 * Return: 1) TRUE if a user was locked by the logged user it self
 *         2) FALSE if a user isnt locked
 *         3) user ID, which locked the user in an other session
 *
 * ** Get all access times **
 *
 * ** unlock all locked user ? **
 *
 * $model->action('user','lock',
 *                array('job' => (string)'unlock_all'));  
 *
 *
 * ** Remove user locks from a given user that locks **
 *
 * $model->action('user','lock',
 *                array('job'    => (string)'unlock_from_user',
 *                      'id_user => (int)user ID that locks));  
 *
 */
class ActionUserLock extends SmartAction
{
    /**
     * User lock actions
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    { 
        $this->deleteExpiredLocks();
        
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
    public function validate( $data = FALSE )
    {
        if( isset($data['id_user']) && @preg_match("/[^0-9]/", $data['id_user']) )
        {
            throw new SmartModelException('Wrong id_user format: '.$data['id_user']);         
        }    
        if( isset($data['by_id_user']) && @preg_match("/[^0-9]/", $data['by_id_user']) )
        {
            throw new SmartModelException('Wrong by_id_user format: '.$data['by_id_user']);         
        }         
        return TRUE;
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
    private function deleteExpiredLocks()
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
                WHERE `by_id_user`={$data['id_user']}";

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