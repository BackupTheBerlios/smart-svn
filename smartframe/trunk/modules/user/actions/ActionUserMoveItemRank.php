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
 * ActionUserMoveFileRank class 
 *
 */
class ActionUserMoveItemRank extends SmartAction
{                          
    /**
     * update user
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    { 
        if(isset($data['id_file']))
        {
            $this->table = 'user_media_file';
            $this->id_item = 'id_file';
        }
        else
        {
            $this->table = 'user_media_pic';
            $this->id_item = 'id_pic';
        }
        
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

        return TRUE;
    }
    /**
     * move file rank up
     *
     * @param array $data
     */  
    private function up($data)
    {
        // Reorder the file rank
        $sql = "SELECT 
                    `rank`-1 AS rank
                FROM {$this->config['dbTablePrefix']}{$this->table}
                WHERE
                    {$this->id_item}={$data[$this->id_item]}
                AND
                    id_user={$data['id_user']}";
        
        $stmt = $this->model->dba->query($sql);
        
        $row = $stmt->fetchAssoc();

        // Reorder the file rank
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}{$this->table}
               SET `rank`=`rank`+1
            WHERE
                `rank`={$row['rank']}
            AND
                `id_user`={$data['id_user']}";

        $this->model->dba->query($sql);   

        if($this->model->dba->affectedRows() == 1)
        {
            $sql = "UPDATE {$this->config['dbTablePrefix']}{$this->table}
                      SET
                        `rank`=`rank`-1
                    WHERE
                        `{$this->id_item}`={$data[$this->id_item]}
                    AND
                        `id_user`={$data['id_user']}";

            $this->model->dba->query($sql);           
        }
    }
    
    private function down(&$data)
    {
        // Reorder the file rank
        $sql = "SELECT 
                    rank+1 AS rank
                FROM {$this->config['dbTablePrefix']}{$this->table}
                WHERE
                    {$this->id_item}={$data[$this->id_item]}
                AND
                    id_user={$data['id_user']}";
        
        $stmt = $this->model->dba->query($sql);
        
        $row = $stmt->fetchAssoc();

        // Reorder the file rank
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}{$this->table}
               SET rank=rank-1
            WHERE
                rank={$row['rank']}
            AND
                id_user={$data['id_user']}";

        $this->model->dba->query($sql);   
        
        if($this->model->dba->affectedRows() == 1)
        {
            $sql = "UPDATE {$this->config['dbTablePrefix']}{$this->table}
                      SET
                        `rank`=`rank`+1
                    WHERE
                        `{$this->id_item}`={$data[$this->id_item]}
                    AND
                        `id_user`={$data['id_user']}";

            $this->model->dba->query($sql);           
        } 
    }    
}

?>
