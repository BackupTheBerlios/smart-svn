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
 * ActionUpdatePicture class 
 *
 */
class ActionUserMovePictureRank extends SmartAction
{
    /**
     * update user
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    { 
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
     * delete picture entry from database and reorder the pictures rank structure
     *
     * @param array $data
     */  
    private function up($data)
    {
        // Reorder the picture rank
        $sql = "SELECT 
                    rank-1 AS rank
                FROM {$this->config['dbTablePrefix']}user_media_pic
                WHERE
                    id_pic={$data['id_pic']}
                AND
                    id_user={$data['id_user']}";
        
        $stmt = $this->model->dba->query($sql);
        
        $row = $stmt->fetchAssoc();

        // Reorder the picture rank
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}user_media_pic
               SET rank=rank+1
            WHERE
                rank={$row['rank']}
            AND
                id_user={$data['id_user']}";

        $this->model->dba->query($sql);   

        if($this->model->dba->affectedRows() == 1)
        {
            $sql = "UPDATE {$this->config['dbTablePrefix']}user_media_pic
                      SET
                        rank=rank-1
                    WHERE
                        `id_pic`={$data['id_pic']}
                    AND
                        `id_user`={$data['id_user']}";

            $this->model->dba->query($sql);           
        }
    }
    
    private function down(&$data)
    {
        // Reorder the picture rank
        $sql = "SELECT 
                    rank+1 AS rank
                FROM {$this->config['dbTablePrefix']}user_media_pic
                WHERE
                    id_pic={$data['id_pic']}
                AND
                    id_user={$data['id_user']}";
        
        $stmt = $this->model->dba->query($sql);
        
        $row = $stmt->fetchAssoc();

        // Reorder the picture rank
        $sql = "
            UPDATE {$this->config['dbTablePrefix']}user_media_pic
               SET rank=rank-1
            WHERE
                rank={$row['rank']}
            AND
                id_user={$data['id_user']}";

        $this->model->dba->query($sql);   
        
        if($this->model->dba->affectedRows() == 1)
        {
            $sql = "UPDATE {$this->config['dbTablePrefix']}user_media_pic
                      SET
                        `rank`=`rank`+1
                    WHERE
                        `id_pic`={$data['id_pic']}
                    AND
                        `id_user`={$data['id_user']}";

            $this->model->dba->query($sql);           
        } 
    }    
}

?>
