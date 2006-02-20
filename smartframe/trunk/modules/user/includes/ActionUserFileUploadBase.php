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
 * ActionUserFileUpload class 
 * Some user action classes may extends this class
 *
 */

class ActionUserFileUploadBase extends SmartAction
{
    /**
     * move_uploaded_file
     *
     * Move an uploaded file
     *
     * @param string $source Source file
     * @param string $destination Destination file
     * @return bool FALSE if it fails else TRUE
     */    
    protected function moveUploadedFile( $source, $destination)
    {
        if(FALSE == @move_uploaded_file($source, $destination))
        {
            if(FALSE == $this->isUploadedFile($source))
            {
                return FALSE;
            } 
            
            return FALSE;
        }

        // set media file rights
        if(!chmod($destination, $this->model->config['media_file_rights']))
        {
            trigger_error("Couldnt change file rights: ".$destination, E_USER_ERROR);
        }
        
        return TRUE;
    }
    
    /**
     * is_file
     *
     * @param string $file File
     * @return bool True on success else false
     */    
    private function isUploadedFile($file)
    {
        clearstatcache();
        return @is_uploaded_file($file);
    }   
    /**
     * getUserMediaFolder
     *
     * @param int $id_user 
     * @return mixed FALSE if fails else name of the media folder
     */     
    protected function getUserMediaFolder( $id_user )
    {
        $sql = "SELECT 
                    `media_folder` 
                FROM 
                    {$this->config['dbTablePrefix']}user_user
                WHERE
                    `id_user`=$id_user";
                  
        $stmt = $this->model->dba->query($sql);

        if($stmt->numRows() > 0)
        {
            $row = $stmt->fetchAssoc();

            if(empty($row['media_folder']))
            {
                return $this->createUserMediaFolder( $id_user );
            }
            else
            {
                return $row['media_folder'];
            }
        }      
        return FALSE;
    }
    /**
     * createUserMediaFolder
     *
     * @param int $id_user 
     * @return string name of the media folder
     */     
    private function createUserMediaFolder( $id_user )
    {
        // create unique folder that dosent exists       
        do
        {
            $folder = SmartCommonUtil::unique_crc32();
        }
        while(@is_dir(SMART_BASE_DIR . 'data/user/' . $folder));
        
        if(!mkdir(SMART_BASE_DIR . 'data/user/' . $folder, $this->config['media_folder_rights']))
        {
            throw new SmartModelException('Cant create media folder: ' . $folder);
        }

        if(!mkdir(SMART_BASE_DIR . 'data/user/' . $folder . '/thumb', $this->config['media_folder_rights']))
        {
            throw new SmartModelException('Cant create media folder: ' . $folder . '/thumb');
        }

        $error = array();
        $this->model->action('user','update',
                             array('error'   => & $error,
                                   'id_user' => $id_user,
                                   'fields'  => array('media_folder' => (string)$folder)));
        
        return $folder;
    }
    /**
     * getUniqueMediaFileName
     * if a media file name exists add a number before the name
     *
     * @param string $media_folder
     * @param string $file_name
     * @return array 
     */     
    protected function getUniqueMediaFileName($media_folder, $file_name)
    {
        $result = array();
        $x = 0;
        
        do
        {
            if($x != 0)
            {
                $prefix = (string)$x . '_';
            }
            else
            {
                $prefix = '';
            }
            
            $result['file_path'] = SMART_BASE_DIR . 'data/user/' . $media_folder . '/' . $prefix . $file_name;
            $x++;
        }
        while(file_exists( $result['file_path'] )); 
        
        $result['file_name'] = $prefix . $file_name;
        
        return $result;
    }                      
}

?>
