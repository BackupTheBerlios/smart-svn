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
 *                      'id_node => (int)node ID to lock,
 *                      'by_id_node' => (int)node ID that locks));
 *
 * Return: 1) TRUE if a node was successfull locked
 *         2) node ID, which locked the node in an other session
 *
 * ** Unlock a given node **
 *
 * $model->action('navigation','lock',
 *                array('job'    => (string)'unlock',
 *                      'id_node => (int)locked node ID )); 
 *
 * 
 * ** Is a node locked ? **
 *
 * $model->action('navigation','lock',
 *                array('job'    => (string)'is_locked',
 *                      'id_node => (int)node ID ));  
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
 *                      'id_node => (int)node ID that locks));  
 *
 */
class ActionNavigationLock extends SmartAction
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
                return $this->lockNode($data);
            case 'unlock':
                $this->unlockNode($data);
                return;
            case 'is_locked':
                return $this->isNodeLocked($data);
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
     * validate data
     *
     * @param array $data 
     * @return bool 
     */    
    public function validate( $data = FALSE )
    {
        if( isset($data['id_node']) && @preg_match("/[^0-9]/", $data['id_node']) )
        {
            throw new SmartModelException('Wrong id_node format: '.$data['id_node']);         
        }    
        if( isset($data['by_id_user']) && @preg_match("/[^0-9]/", $data['by_id_user']) )
        {
            throw new SmartModelException('Wrong by_id_user format: '.$data['by_id_user']);         
        }         
        return TRUE;
    }

    /**
     * Lock a node for modifying
     *
     * @param array $data Node data
     */    
    private function lockNode($data)
    {
        $result = $this->isNodeLocked($data);
        // False = the node isnt locked
        // True = the node is locked by the logged user
        // if not locked by the logged user, $result
        // contains the id of the user which locks
        if(($result != FALSE) && ($result != TRUE))
        {
            return $result;
        }
        
        if($result == FALSE)
        {
            $sql = "INSERT INTO {$this->config['dbTablePrefix']}navigation_node_lock
                        (`id_node`,`lock_time`,`by_id_user`)
                    VALUES
                       ({$data['id_node']},NOW(),{$data['by_id_user']})";

            $this->model->dba->query($sql); 
        }
        return TRUE;
    }
    
    /**
     * Unlock a node
     *
     * @param array $data
     */    
    private function unlockNode($data)
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_node_lock
                  WHERE
                   `id_node`={$data['id_node']}";

        $this->model->dba->query($sql);       
    }   
    
    /**
     * Delete all locks which are older than 1 hour
     *
     */    
    private function deleteExpiredLocks()
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_node_lock
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
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_node_lock";

        $this->model->dba->query($sql);        
    }      

    /**
     * Unlock all entries which a user has locked
     *
     */    
    private function unlockByIdUser($data)
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_node_lock
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
    private function isNodeLocked($data)
    {
        $sql = "SELECT 
                    `by_id_user` 
                FROM 
                    {$this->config['dbTablePrefix']}navigation_node_lock
                WHERE
                   `id_node`={$data['id_node']}";

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