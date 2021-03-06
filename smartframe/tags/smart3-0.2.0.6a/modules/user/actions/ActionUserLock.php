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
 * $res = $model->action('user','lock',
 *                       array('job'    => (string),    // value: 'lock'
 *                             'id_user => (int),       // user ID to lock
 *                             'by_id_user' => (int))); // user ID that locks
 *
 * Return: 1) TRUE if a user was successfull locked
 *         2) user ID, which locked the user in an other session
 *
 * ** Unlock a given user **
 *
 * $model->action('user','lock',
 *                array('job'    => (string), // value: 'unlock'
 *                      'id_user => (int)));  // locked user ID
 *
 * 
 * ** Is a user locked ? **
 *
 * $res = $model->action('user','lock',
 *                       array('job'    => (string), // value: 'is_locked'
 *                             'id_user => (int)));  // user ID
 *
 * Return: 1) TRUE if a user was locked by the logged user it self
 *         2) FALSE if a user isnt locked
 *         3) user ID, which locked the user in an other session
 *
 *
 * ** unlock all locked user ? **
 *
 * $model->action('user','lock',
 *                array('job' => (string)));  // value: 'unlock_all'
 *
 *
 * ** Remove user locks from a given user that locks **
 *
 * $model->action('user','lock',
 *                array('job'    => (string), // value: 'unlock_from_user'
 *                      'id_user => (int)));  // user ID that locks 
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
                throw new SmartModelException('"job" not available: '.$data['job']); 
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
        if( isset($data['id_user']) && !is_int($data['id_user']) )
        {
            throw new SmartModelException('Wrong "id_user" format or it isnt set');         
        }    
        if( isset($data['by_id_user']) && !is_int($data['by_id_user']) )
        {
            throw new SmartModelException('Wrong "by_id_user" format or it isnt set');         
        } 
        if( isset($data['job']) && !is_string($data['job']) )
        {
            throw new SmartModelException('Wrong "job" format or it isnt set');         
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
        if(($result !== FALSE) && ($result !== TRUE))
        {
            return $result;
        }
        
        if($result == FALSE)
        {
            $sql = "REPLACE INTO {$this->config['dbTablePrefix']}user_lock
                        (`id_user`,`lock_time`,`by_id_user`)
                    VALUES
                       ({$data['id_user']},NOW(),{$data['by_id_user']})";

            $this->model->dba->query($sql); 
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

        $this->model->dba->query($sql);       
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

        $this->model->dba->query($sql);         
    }  
    
    /**
     * Unlock all entries
     *
     */    
    private function unlockAll()
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_lock";

        $this->model->dba->query($sql);        
    }      

    /**
     * Unlock all entries which a user has locked
     *
     */    
    private function unlockByIdUser($data)
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_lock
                WHERE `by_id_user`={$data['id_user']}";

        $this->model->dba->query($sql);        
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

        $result = $this->model->dba->query($sql); 
       
        if($result->numRows() == 1)
        {            
            $row = $result->fetchAssoc();
            
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