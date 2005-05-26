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
    
    protected function getUserMediaFolder( $id_user )
    {
        $sql = "SELECT 
                    `media_folder` 
                FROM 
                    {$this->config['dbTablePrefix']}user_user
                WHERE
                    `id_user`=?";
                  
        $stmt = $this->model->db->prepareStatement($sql, ResultSet::FETCHMODE_ASSOC);

        $stmt->setInt(1, $id_user);
        
        $result = $stmt->executeQuery();

        if($result->getRecordCount() > 0)
        {
            $result->first();
            $field = $result->getRow();

            if(empty($field['media_folder']))
            {
                return $this->createUserMediaFolder( $id_user );
            }
            else
            {
                return $field['media_folder'];
            }
        }                    
    }
    
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

        $this->model->action('user',
                             'update',
                             array('error'   => & $data['error'],
                                   'id_user' => $id_user,
                                   'user'    => array('media_folder' => $folder)));
        
        return $folder;
    }
    
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
