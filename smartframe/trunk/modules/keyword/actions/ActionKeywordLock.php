<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ActionNavigationLock class 
 *
 */

/**
 * USE:
 *
 * ** Lock a given node by an node**
 *
 * $model->action('navigation','lock',
 *                array('job'    => (string)'lock',
 *                      'id_key => (int)node ID to lock,
 *                      'by_id_key' => (int)node ID that locks));
 *
 * Return: 1) TRUE if a node was successfull locked
 *         2) node ID, which locked the node in an other session
 *
 * ** Unlock a given node **
 *
 * $model->action('navigation','lock',
 *                array('job'    => (string)'unlock',
 *                      'id_key => (int)locked node ID )); 
 *
 * 
 * ** Is a node locked ? **
 *
 * $model->action('navigation','lock',
 *                array('job'    => (string)'is_locked',
 *                      'id_key => (int)node ID ));  
 *
 * Return: 1) TRUE if a node was locked by the logged node it self
 *         2) FALSE if a node isnt locked
 *         3) node ID, which locked the node in an other session
 *
 * ** Get all access times **
 *
 * ** unlock all locked node ? **
 *
 * $model->action('navigation','lock',
 *                array('job' => (string)'unlock_all'));  
 *
 *
 * ** Remove node locks from a given node that locks **
 *
 * $model->action('navigation','lock',
 *                array('job'    => (string)'unlock_from_node',
 *                      'id_key => (int)node ID that locks));  
 *
 */
class ActionKeywordLock extends SmartAction
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
                return $this->lockKeyword($data);
            case 'unlock':
                $this->unlockKeyword($data);
                return;
            case 'is_locked':
                return $this->isKeywordLocked($data);
            case 'unlock_all':
                $this->unlockAll(); 
                return;
            case 'unlock_from_user':
                $this->unlockByIdUser($data); 
                return;                
            default:
                throw new SmartModelException('Action not available: '.$data['job']); 
        }
        
        return TRUE;
    }
    
    /**
     * validate data
     *
     * @param array $data 
     * @return bool 
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['job']))
        {
            throw new SmartModelException('"job" isnt set');
        }
        elseif(!is_string($data['job']))
        {
            throw new SmartModelException('"job" isnt from type string');
        } 
        
        if( isset($data['id_key']) && !is_int($data['id_key']) )
        {
            throw new SmartModelException('"id_key" isnt from type int');         
        }    
        if( isset($data['by_id_user']) && !is_int($data['by_id_user']) )
        {
            throw new SmartModelException('"by_id_user" isnt from type int');         
        }         
        return TRUE;
    }

    /**
     * Lock a node for modifying
     *
     * @param array $data Node data
     */    
    private function lockKeyword($data)
    {
        $result = $this->isKeywordLocked($data);
        // False = the node isnt locked
        // True = the node is locked by the logged user
        // if not locked by the logged user, $result
        // contains the id of the user which locks
        if(($result !== FALSE) && ($result !== TRUE))
        {
            return $result;
        }
        
        if($result == FALSE)
        {
            $sql = "REPLACE INTO {$this->config['dbTablePrefix']}keyword_lock
                        (`id_key`,`lock_time`,`by_id_user`)
                    VALUES
                       ({$data['id_key']},NOW(),{$data['by_id_user']})";

            $this->model->dba->query($sql); 
        }
        return TRUE;
    }
    
    /**
     * Unlock a node
     *
     * @param array $data
     */    
    private function unlockKeyword($data)
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}keyword_lock
                  WHERE
                   `id_key`={$data['id_key']}";

        $this->model->dba->query($sql);       
    }   
    
    /**
     * Delete all locks which are older than 1 hour
     *
     */    
    private function deleteExpiredLocks()
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}keyword_lock
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
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}keyword_lock";

        $this->model->dba->query($sql);        
    }      

    /**
     * Unlock all entries which a user has locked
     *
     */    
    private function unlockByIdUser($data)
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}keyword_lock
                WHERE `by_id_user`={$data['id_user']}";

        $this->model->dba->query($sql);        
    }
    
    /**
     * Check if a node is locked and if yes by which id_user
     *
     * @param array $data User data
     * @param mixed FALSE if not locked True if locked by the logged user
     *              else id_user of the user who locks
     */    
    private function isKeywordLocked($data)
    {
        $sql = "SELECT 
                    `by_id_user` 
                FROM 
                    {$this->config['dbTablePrefix']}keyword_lock
                WHERE
                   `id_key`={$data['id_key']}";

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