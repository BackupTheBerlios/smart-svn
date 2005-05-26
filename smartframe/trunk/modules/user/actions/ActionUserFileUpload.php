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
 *
 */
include_once(SMART_BASE_DIR . 'modules/user/includes/ActionUserFileUploadBase.php');

class ActionUserFileUpload extends ActionUserFileUploadBase
{
    /**
     * add user
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $media_folder = $this->getUserMediaFolder( $data['id_user'] );
        
        $file_info = $this->getUniqueMediaFileName($media_folder, $_FILES[$data['post_name']]['name']);

        if(FALSE == $this->moveUploadedFile($_FILES[$data['post_name']]['tmp_name'], $file_info['file_path']))
        { 
            throw new SmartModelException ('Cant upload file');   
        }

        if( $data['post_name'] == 'logo' )  
        {
            $this->model->action('user',
                                 'update',
                                 array('error'   => & $data['error'],
                                       'id_user' => $data['id_user'],
                                       'user'    => array('logo' => $file_info['file_name'])));
        }
        
        
        return TRUE;
    }
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate(  $data = FALSE  )
    {
        // init error var
        
        // check if user exists
        if( empty($data['post_name']) )
        {        
            throw new SmartModelException ('post name must be defined in view class'); 
        }
        // validate user name
        elseif( !isset($_FILES[$data['post_name']]) )
        {
            throw new SmartModelException ('You have to select a local file to upload');
        }        
        
        return TRUE;
    }
}

?>