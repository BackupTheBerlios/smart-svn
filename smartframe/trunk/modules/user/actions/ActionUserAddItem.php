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
 * ActionUserAddItem class 
 *
 */
include_once(SMART_BASE_DIR . 'modules/user/includes/ActionUserFileUploadBase.php');

class ActionUserAddItem extends ActionUserFileUploadBase
{
    /**
     * add user picture or file
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

        if(!isset($data['item']))
        {
            throw new SmartModelException("No 'item' defined");
        }
        elseif(($data['item'] != 'picture') && ($data['item'] != 'file'))
        {
            throw new SmartModelException("'item' must be 'file' or 'picture'");
        }
        if(!isset($data['id_user']))
        {
            throw new SmartModelException("No 'id_user' defined");
        }
        elseif(preg_match("/[^0-9]/",$data['id_user']))
        {
            throw new SmartModelException("'id_user' isnt numeric");
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
        $rank = $this->getNewLastRank( $data['id_user'], 'user_media_file' );

        $_file = SMART_BASE_DIR . "data/user/" . $media_folder . '/' . $file_info['file_name'];
        
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
       
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}user_media_file
                   (id_user,rank,file,size,mime)
                  VALUES
                   ({$data['id_user']},
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
        $image_source = SMART_BASE_DIR . "data/user/" . $media_folder . '/' . $file_info['file_name'];
        $image_dest_folder   = SMART_BASE_DIR . "data/user/" . $media_folder . '/thumb';
        
        $pic_info = array();
        
        $this->model->action('common','imageThumb',
                             array('imgSource'     => $image_source,
                                   'imgDestName'   => $file_info['file_name'],
                                   'imgDestWidth'  => $this->config['user']['thumb_width'],
                                   'imgDestFolder' => $image_dest_folder,
                                   'info'          => &$pic_info));  
        
        $rank = $this->getNewLastRank( $data['id_user'], 'user_media_pic' );
                
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}user_media_pic
                   (id_user,rank,file,size,mime)
                  VALUES
                   ({$data['id_user']},
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
    function getNewLastRank( $id_user, $table )
    {
        $sql = "
            SELECT
                rank
            FROM
                {$this->config['dbTablePrefix']}{$table}  
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
