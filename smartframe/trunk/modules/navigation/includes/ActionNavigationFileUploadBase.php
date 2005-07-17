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
 * ActionNavigationFileUpload class 
 * Some Navigation action classes may extends this class
 *
 */

class ActionNavigationFileUploadBase extends SmartAction
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
    
    protected function getNodeMediaFolder( $id_node )
    {
        $sql = "SELECT 
                    `media_folder` 
                FROM 
                    {$this->config['dbTablePrefix']}navigation_node
                WHERE
                    `id_node`={$id_node}";
                  
        $stmt = $this->model->dba->query($sql);

        if($stmt->numRows() > 0)
        {
            $row = $stmt->fetchAssoc();

            if(empty($row['media_folder']))
            {
                return $this->createNodeMediaFolder( $id_node );
            }
            else
            {
                return $row['media_folder'];
            }
        }      
        return FALSE;
    }
    
    private function createNodeMediaFolder( $id_node )
    {
        // create unique folder that dosent exists       
        do
        {
            $folder = SmartCommonUtil::unique_crc32();
        }
        while(@is_dir(SMART_BASE_DIR . 'data/navigation/' . $folder));
        
        if(!mkdir(SMART_BASE_DIR . 'data/navigation/' . $folder, $this->config['media_folder_rights']))
        {
            throw new SmartModelException('Cant create media folder: ' . $folder);
        }

        if(!mkdir(SMART_BASE_DIR . 'data/navigation/' . $folder . '/thumb', $this->config['media_folder_rights']))
        {
            throw new SmartModelException('Cant create media folder: ' . $folder . '/thumb');
        }

        $this->model->action('navigation',
                             'updateNode',
                             array('error'   => & $data['error'],
                                   'id_node' => $id_node,
                                   'fields'  => array('media_folder' => $folder)));
        
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
            
            $result['file_path'] = SMART_BASE_DIR . 'data/navigation/' . $media_folder . '/' . $prefix . $file_name;
            $x++;
        }
        while(file_exists( $result['file_path'] )); 
        
        $result['file_name'] = $prefix . $file_name;
        
        return $result;
    }                      
}

?>
