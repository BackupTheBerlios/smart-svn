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
 * ActionUserAdd class 
 *
 */
include_once(SMART_BASE_DIR . 'modules/user/includes/ActionUserFileUploadBase.php');

class ActionUserAddFile extends ActionUserFileUploadBase
{
    /**
     * add user
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    { 
        $media_folder = $this->getUserMediaFolder( $data['id_user'] );
        
        $file_info = $this->getUniqueMediaFileName($media_folder, $_FILES[$data['postName']]['name']);

        if(FALSE == $this->moveUploadedFile($_FILES[$data['postName']]['tmp_name'], $file_info['file_path']))
        { 
            throw new SmartModelException ('Cant upload file');   
        }
                                   
        $rank = $this->getNewLastRank( $data['id_user'] );
               
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}user_media_file
                   (id_user,rank,file,size,mime)
                  VALUES
                   ({$data['id_user']},
                    {$rank},
                    '{$file_info['file_name']}',
                    0,
                    '' )";

        $this->model->dba->query($sql);                    

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
        // check if user exists
        if( !isset($data['postName']) || empty($data['postName']) )
        {        
            throw new SmartModelException ('"post_name" must be defined in view class'); 
        }
        // validate user name
        elseif( !isset($_FILES[$data['postName']]) )
        {
            throw new SmartModelException ('You have to select a local file to upload');
        }    
        
        return TRUE;
    }
    
    /**
     * get new last picture rank
     *
     * @param int $id_user User ID
     * @return int Rank number
     */    
    function getNewLastRank( $id_user )
    {
        $sql = "
            SELECT
                rank
            FROM
                {$this->config['dbTablePrefix']}user_media_file  
            WHERE
                id_user='$id_user'
            ORDER BY 
                rank DESC
            LIMIT 1";
        
        $stmt = $this->model->dba->query($sql);

        if($stmt->numRows() == 1)
        {
            $row = $stmt->fetchAssoc();
            return ++$row['rank'];
        }
        return 1;    
    } 
    
}

?>
