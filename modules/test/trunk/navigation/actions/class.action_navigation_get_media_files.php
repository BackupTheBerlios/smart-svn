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
 * action_navigation_get_media_files class 
 *
 */

class action_navigation_get_media_files extends action
{
    /**
     * Fill up an array with navigation elements
     *
     * Structure of the $data array:
     * $data['result'] - name of the navigation array
     * $data['status'] - status of the nodes to get
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    {
        $_result = & $this->B->$data['result'];
        $_result = array();
  
        if(isset( $data['media_folder']))
        {
            $media_folder = $data['media_folder'];
        }
        else
        {
            $media_folder = '';
        }  
  
        $media_directory = & dir( SF_BASE_DIR . 'data/media' . $media_folder);
        while (false != ($media_filename = $media_directory->read()))
        {
            if ( ( $media_filename == '.' ) || ( $media_filename == '..' ) )
            {
                continue;
            }  
            $tmp = array();
            if ( !@is_dir( SF_BASE_DIR . 'data/media' . $media_folder . '/' . $media_filename) )
            {
                $tmp['path'] = 'data/media' .$data['media_folder'] . '/' . $media_filename;
                $tmp['file'] = $media_filename;
            }
            $_result[] = $tmp;
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

        $uploaddir  = SF_BASE_DIR . 'data/media' . $media_folder;    

        if( !is_dir($uploaddir) )
        {
            $this->_error = "This folder dosent exsists: " . $uploaddir;
            return SF_NO_VALID_ACTION;                        
        }
            
        return SF_IS_VALID_ACTION;
    }    
}

?>