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
 * action_navigation_upload_media_file class 
 *
 */

class action_navigation_upload_media_file extends action
{
    /**
     * Upload a file to the server
     *
     * Structure of the $data array:
     * $data['file'] = $_FILES['file']
     * $data['media_folder'] = relative path of the media folder from the installation root
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
        
        $uploaddir  = SF_BASE_DIR . 'data/media' . $media_folder . '/';
        $uploadfile = $uploaddir . $data['media_file']['name'];
        
        // check if such a file exsists
        while(@file_exists($uploadfile))
        {
            // add datetime before file name
            $_time = date( "Y-m-d_H-i-s", time() );
            $uploadfile = $uploaddir . $_time . '__' . $data['media_file']['name'];   
        }

        if (!move_uploaded_file($data['media_file']['tmp_name'], $uploadfile)) 
        {
            $this->_error = "Error during file upload. Please try again ::: " . $data['media_file']['error'];
            return SF_NO_VALID_ACTION;
        } 
        
        @chmod( $uploadfile, SF_FILE_MODE);
        
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
        
        if( empty($data['media_file']['tmp_name']) )
        {
            $this->_error = "You have to select a file to upload!";
            return SF_NO_VALID_ACTION;
        }

        if( preg_match("/\.\./", $data['media_file']['name']) )
        {
            $this->_error = "This media file name isnt allowed: " . $data['media_file']['name'];
            return SF_NO_VALID_ACTION;            
        } 

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

        $uploaddir  = SF_BASE_DIR . 'data/media' . $media_folder;    

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