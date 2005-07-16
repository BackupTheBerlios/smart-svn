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
 * ActionNavigationMoveItemRank class 
 *
 * move rank of node pictures or files
 *
 */
class ActionNavigationMoveItemRank extends SmartAction
{                          
    /**
     * 
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    { 
        // set table name and item reference
        if(isset($data['id_file']))
        {
            $this->table = 'navigation_media_file';
            $this->id_item = 'id_file';
        }
        else
        {
            $this->table = 'navigation_media_pic';
            $this->id_item = 'id_pic';
        }
        
        // switch to dir methode
        switch($data['dir'])
        {
            case 'up':
                    $this->up($data);
                break;
            case 'down':
                    $this->down($data);
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
        if( !isset($data['id_node']) )
        {        
            throw new SmartModelException ('"id_node" must be defined'); 
        } 
        elseif(@preg_match("/[^0-9]/", $data['id_node'])  )
        {        
            throw new SmartModelException ('"id_node" must be an integer'); 
        } 
        
        if( !isset($data['id_file']) && !isset($data['id_pic']) )
        {        
            throw new SmartModelException ('"id_file" or "id_pic" must be defined'); 
        }
        
        if( isset($data['id_file']) && @preg_match("/[^0-9]/", $data['id_file'])  )
        {        
            throw new SmartModelException ('"id_file" must be an integer'); 
        }  
        
        if( isset($data['id_pic']) && @preg_match("/[^0-9]/", $data['id_pic'])  )
        {        
            throw new SmartModelException ('"id_pic" must be an integer'); 
        }  
        
        if( !isset($data['dir']) )
        {        
            throw new SmartModelException ('"dir" must be defined'); 
        }  
        elseif( ($data['dir'] != 'up') && ($data['dir'] != 'down'))
        {        
            throw new SmartModelException ('"dir" value must be "up" or "down"'); 
        }   
        
        return TRUE;
    }
    /**
     * move file rank up
     *
     * @param array $data
     */  
    private function up($data)
    {
        // get rank of neighbour file
        $sql = "SELECT 
                    `rank`-1 AS rank
                FROM {$this->config['dbTablePrefix']}{$this->table}
                WHERE
                    {$this->id_item}={$data[$this->id_item]}
                AND
                    id_node={$data['id_node']}";
        
        $stmt = $this->model->dba->query($sql);
        
        $row = $stmt->fetchAssoc();

        // move rank of neighbour file
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}{$this->table}
               SET `rank`=`rank`+1
            WHERE
                `rank`={$row['rank']}
            AND
                `id_node`={$data['id_node']}";

        $this->model->dba->query($sql);   

        if($this->model->dba->affectedRows() == 1)
        {
            // update the file rank to move
            $sql = "UPDATE {$this->config['dbTablePrefix']}{$this->table}
                      SET
                        `rank`=`rank`-1
                    WHERE
                        `{$this->id_item}`={$data[$this->id_item]}
                    AND
                        `id_node`={$data['id_node']}";

            $this->model->dba->query($sql);           
        }
    }
    /**
     * move file rank down
     *
     * @param array $data
     */     
    private function down(&$data)
    {
         // get rank of neighbour file
        $sql = "SELECT 
                    rank+1 AS rank
                FROM {$this->config['dbTablePrefix']}{$this->table}
                WHERE
                    {$this->id_item}={$data[$this->id_item]}
                AND
                    id_node={$data['id_node']}";
        
        $stmt = $this->model->dba->query($sql);
        
        $row = $stmt->fetchAssoc();

        // move rank of neighbour file
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}{$this->table}
               SET rank=rank-1
            WHERE
                rank={$row['rank']}
            AND
                id_node={$data['id_node']}";

        $this->model->dba->query($sql);   
        
        if($this->model->dba->affectedRows() == 1)
        {
            // update the file rank to move
            $sql = "UPDATE {$this->config['dbTablePrefix']}{$this->table}
                      SET
                        `rank`=`rank`+1
                    WHERE
                        `{$this->id_item}`={$data[$this->id_item]}
                    AND
                        `id_node`={$data['id_node']}";

            $this->model->dba->query($sql);           
        } 
    }    
}

?>
