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
 * ActionMiscAddItem class 
 *
 * $model->action('misc','addItem',
 *                array('error' => & array(),
 *                      'item'  => string,      // 'picture' or 'file'
 *                      'postName' => string,   // $_FILES[$data['postName']]
 *                      'id_text'  => int))
 *
 */
include_once(SMART_BASE_DIR . 'modules/misc/includes/ActionMiscFileUploadBase.php');

class ActionMiscAddItem extends ActionMiscFileUploadBase
{
    /**
     * add Misc picture or file
     *
     * @param array $data
     * @return int Misc id or false on error
     */
    function perform( $data = FALSE )
    { 
        $media_folder = $this->getMiscMediaFolder( $data['id_text'] );
        
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
     * validate Misc data
     *
     * @param array $data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        if(!isset($data['error']))
        {
            throw new SmartModelException("'error' var isnt set!");
        }
        elseif(!is_array($data['error']))
        {
            throw new SmartModelException("'error' var isnt from type array!");
        }
        
        // check if postName exists
        if( !isset($data['postName']) || empty($data['postName']) )
        {        
            throw new SmartModelException ('"post_name" must be defined in view class'); 
        }
        // validate postName name
        elseif( !isset($_FILES[$data['postName']]) )
        {
            $data['error'][] = 'You have to select a local file to upload';
        }    
        elseif( !file_exists($_FILES[$data['postName']]['tmp_name']) )
        {
            $data['error'][] = 'File upload failed';
        }  
        
        if(!isset($data['item']))
        {
            throw new SmartModelException("No 'item' defined");
        }
        elseif(($data['item'] != 'picture') && ($data['item'] != 'file'))
        {
            throw new SmartModelException("'item' must be 'file' or 'picture'");
        }
        if(!isset($data['id_text']))
        {
            throw new SmartModelException("No 'id_text' defined");
        }
        elseif(!is_int($data['id_text']))
        {
            throw new SmartModelException("'id_text' isnt from type int");
        }  
        elseif(($data['item'] == 'file') && ($this->config['misc']['file_size_max'] <= filesize($_FILES[$data['postName']]['tmp_name'])))
        {
            $data['error'][] = "Max file size allowed: {$this->config['misc']['file_size_max']} bytes";
        }
        elseif(($data['item'] == 'picture') && ($this->config['misc']['img_size_max'] <= filesize($_FILES[$data['postName']]['tmp_name'])))
        {
            $data['error'][] = "Max picture size allowed: {$this->config['misc']['img_size_max']} bytes";
        }

        if(count($data['error']) > 0)
        {
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
        $rank = $this->getNewLastRank( $data['id_text'], 'misc_text_file' );

        $_file = SMART_BASE_DIR . "data/misc/" . $media_folder . '/' . $file_info['file_name'];
        
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
       
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}misc_text_file
                   (id_text,rank,file,size,mime)
                  VALUES
                   ({$data['id_text']},
                    {$rank},
                    '{$file_info['file_name']}',
                    {$file_size},
                    '{$type}' )";

        $this->model->dba->query($sql);     
    }
    /**
     * add Misc picture db reference
     *
     * @param array $data action data array
     * @param string &$media_folder
     * @param array &$file_info
     */       
    private function addPicture( &$data, &$media_folder, &$file_info )
    {
        $image_source = SMART_BASE_DIR . "data/misc/" . $media_folder . '/' . $file_info['file_name'];
        $image_dest_folder   = SMART_BASE_DIR . "data/misc/" . $media_folder . '/thumb';
        
        $pic_info = array();
        
        // get image width and height
        if(FALSE !== ($info = getimagesize( $image_source )))
        {
            $img_width  = $info[0];
            $img_height = $info[1];
        }
        else
        {
            $img_width  = 0;
            $img_height = 0;
        }
        
        $this->model->action('common','imageThumb',
                             array('error'         => & $data['error'],
                                   'imgSource'     => (string)$image_source,
                                   'imgDestName'   => (string)$file_info['file_name'],
                                   'imgDestWidth'  => (int)$this->config['misc']['thumb_width'],
                                   'imgDestFolder' => (string)$image_dest_folder,
                                   'info'          => &$pic_info));  
        
        $rank = $this->getNewLastRank( $data['id_text'], 'misc_text_pic' );
                
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}misc_text_pic
                   (id_text,rank,file,size,mime,width,height)
                  VALUES
                   ({$data['id_text']},
                    {$rank},
                    '{$file_info['file_name']}',
                    {$pic_info['size']},
                    '{$pic_info['mime']}',
                    {$img_width},
                    {$img_height})";

        $this->model->dba->query($sql);                                       
    }
    
    /**
     * get new last item rank
     *
     * @param int $id_text
     * @param string $table db table name
     * @return int Rank number
     */    
    function getNewLastRank( $id_text, $table )
    {
        $sql = "
            SELECT
                rank
            FROM
                {$this->config['dbTablePrefix']}{$table}  
            WHERE
                id_text={$id_text}
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
