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
 * ActionNavigationAddItem class 
 *
 */
include_once(SMART_BASE_DIR . 'modules/navigation/includes/ActionNavigationFileUploadBase.php');

class ActionNavigationAddItem extends ActionNavigationFileUploadBase
{
    /**
     * add node picture or file
     *
     * @param array $data
     * @return int node id or false on error
     */
    function perform( $data = FALSE )
    { 
        $media_folder = $this->getNodeMediaFolder( $data['id_node'] );
        
        $file_info = $this->getUniqueMediaFileName($media_folder, $_FILES[$data['postName']]['name']);

        if(FALSE == $this->moveUploadedFile($_FILES[$data['postName']]['tmp_name'], $file_info['file_path']))
        { 
            throw new SmartModelException ('Cant upload file');   
        }
        
        // set table name and item reference
        if($data['item'] == 'picture')
        {
            $this->addPicture($data, $media_folder, $file_info);
        }
        else
        {
            $this->addFile($data, $media_folder, $file_info);
        }
        
        return TRUE;
    }
    
    /**
     * validate node data
     *
     * @param array $data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        // check if postName exists
        if( !isset($data['postName']) || empty($data['postName']) )
        {        
            throw new SmartModelException ('"post_name" must be defined in view class'); 
        }
        // validate postName name
        elseif( !isset($_FILES[$data['postName']]) )
        {
            throw new SmartModelException ('You have to select a local file to upload');
        }    

        if(!isset($data['item']))
        {
            throw new SmartModelException("No 'item' defined");
        }
        elseif(($data['item'] != 'picture') && ($data['item'] != 'file'))
        {
            throw new SmartModelException("'item' must be 'file' or 'picture'");
        }
        if(!isset($data['id_node']))
        {
            throw new SmartModelException("No 'id_node' defined");
        }
        elseif(preg_match("/[^0-9]/",$data['id_node']))
        {
            throw new SmartModelException("'id_node' isnt numeric");
        }  
        elseif(($data['item'] == 'file') && ($this->config['navigation']['file_size_max'] <= filesize($_FILES[$data['postName']]['tmp_name'])))
        {
            $data['error'] = "Max file size allowed: {$this->config['navigation']['file_size_max']} bytes";
            return FALSE;
        }
        elseif(($data['item'] == 'picture') && ($this->config['navigation']['img_size_max'] <= filesize($_FILES[$data['postName']]['tmp_name'])))
        {
            $data['error'] = "Max picture size allowed: {$this->config['navigation']['img_size_max']} bytes";
            return FALSE;
        }
        
        return TRUE;
    }
    /**
     * add user file db reference
     *
     * @param array $data User data
     * @param string &$media_folder
     * @param array &$file_info
     */   
    private function addFile( &$data, &$media_folder, &$file_info )
    {
        $rank = $this->getNewLastRank( $data['id_node'], 'user_media_file' );

        $_file = SMART_BASE_DIR . "data/navigation/" . $media_folder . '/' . $file_info['file_name'];
        
        // get mime type
        $type = '';
        if (function_exists('mime_content_type')) 
        {
            $type = mime_content_type($_file);
        }
        else
        {
            $type = $this->getMime( $file_info['file_name'] );
        }

        $file_size = filesize($_file);
       
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}navigation_media_file
                   (id_node,rank,file,size,mime)
                  VALUES
                   ({$data['id_node']},
                    {$rank},
                    '{$file_info['file_name']}',
                    {$file_size},
                    '{$type}' )";

        $this->model->dba->query($sql);     
    }
    /**
     * add user picture db reference
     *
     * @param array $data User data
     * @param string &$media_folder
     * @param array &$file_info
     */       
    private function addPicture( &$data, &$media_folder, &$file_info )
    {
        $image_source = SMART_BASE_DIR . "data/navigation/" . $media_folder . '/' . $file_info['file_name'];
        $image_dest_folder   = SMART_BASE_DIR . "data/navigation/" . $media_folder . '/thumb';
        
        $pic_info = array();
        
        $this->model->action('common','imageThumb',
                             array('imgSource'     => $image_source,
                                   'imgDestName'   => $file_info['file_name'],
                                   'imgDestWidth'  => $this->config['navigation']['thumb_width'],
                                   'imgDestFolder' => $image_dest_folder,
                                   'info'          => &$pic_info));  
        
        $rank = $this->getNewLastRank( $data['id_node'], 'navigation_media_pic' );
                
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}navigation_media_pic
                   (id_node,rank,file,size,mime)
                  VALUES
                   ({$data['id_node']},
                    {$rank},
                    '{$file_info['file_name']}',
                    {$pic_info['size']},
                    '{$pic_info['mime']}' )";

        $this->model->dba->query($sql);                                       
    }
    
    /**
     * get new last item rank
     *
     * @param int $id_user User ID
     * @return int Rank number
     */    
    function getNewLastRank( $id_node, $table )
    {
        $sql = "
            SELECT
                rank
            FROM
                {$this->config['dbTablePrefix']}{$table}  
            WHERE
                id_node={$id_node}
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
     
    /**
     * get_mime
     *
     * Get the mime type of a file. A file type is identified by its extension
     *
     * @param string $file file name
     * @return string Mime type
     */    
    private function getMime( &$file )
    {
        include_once(SMART_BASE_DIR.'modules/common/includes/SmartCommonFileMime.php');
        return SmartCommonFileMime::getMime($file);
    }        
}

?>
