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
 * action_navigation_delete_media_file class 
 *
 */

class action_navigation_delete_media_file extends action
{
    /**
     * Delete a file from the media directory
     *
     * Structure of the $data array:
     * $data['media_file'] - File name
     * $data['media_folder'] - relative path of the media folder from the installation root
     * $data['error'] - where error messages goes
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    {
        if(isset( $data['media_folder']))
        {
            $media_folder = $data['media_folder'];
        }
        else
        {
            $media_folder = '';
        }
        
        $media_dir  = SF_BASE_DIR . 'data/media' . $media_folder . '/';
        $media_file = $media_dir . $data['media_file'];
        
        if( !@unlink($media_file) )
        {
            $this->_error = "Couldnt delete file ::: " . $media_file;
            return SF_NO_VALID_ACTION;        
        }
        
        return SF_IS_VALID_ACTION;
    }

    /**
     * Validate data
     *
     * @param array $data
     * @return bool
     */
    function validate( $data = FALSE )
    {
        $this->_error = & $this->B->$data['error'];
        $this->_error = FALSE; 

        if(isset( $data['media_folder']))
        {
            $media_folder = $data['media_folder'];
        }
        else
        {
            $media_folder = '';
        }
        
        // Check media folder settings
        //
        if(!empty( $media_folder ))
        {
            if( preg_match("/\.\./", $data['media_folder']) )
            {
                $this->_error = "This media folder path isnt allowed: " . $data['media_folder'];
                return SF_NO_VALID_ACTION;             
            }          
        }        
        
        // check media file name
        if( empty( $data['media_file'] ) )
        {
            $this->_error = "Empty media file name!";
            return SF_NO_VALID_ACTION;             
        }

        if( preg_match("/\.\./", $data['media_file']) )
        {
            $this->_error = "This media file cant be deleted: " . $data['media_fiele'];
            return SF_NO_VALID_ACTION;             
        }   

        $uploaddir  = SF_BASE_DIR . 'data/media' . $media_folder;    

        if( !file_exists( $uploaddir . '/' . $data['media_file']) )
        {
            $this->_error = "This file dosent exists: " . $uploaddir . '/' . $data['media_file'];
            return SF_NO_VALID_ACTION;                         
        }

        if( !is_dir($uploaddir) )
        {
            $this->_error = "This folder dosent exsists: " . $uploaddir;
            return SF_NO_VALID_ACTION;                         
        }

        if( !is_writeable($uploaddir) )
        {
            $this->_error = "This folder isnt writeable: " . $uploaddir;
            return SF_NO_VALID_ACTION;                        
        }  
            
        return SF_IS_VALID_ACTION;
    }
}

?>