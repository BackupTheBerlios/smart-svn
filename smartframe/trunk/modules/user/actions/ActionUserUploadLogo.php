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
 * ActionUserUploadLogo class 
 *
 */
include_once(SMART_BASE_DIR . 'modules/user/includes/ActionUserFileUploadBase.php');

class ActionUserUploadLogo extends ActionUserFileUploadBase
{
    /**
     * add user logo picture
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $media_folder = $this->getUserMediaFolder( $data['id_user'] );
        
        $file_info = $this->getUniqueMediaFileName($media_folder, $_FILES[$data['postName']]['name']);

        if(FALSE == $this->moveUploadedFile($_FILES[$data['postName']]['tmp_name'], $file_info['file_path']))
        { 
            throw new SmartModelException ('Cant upload file');   
        }
        
        $error = array();
        $this->model->action('user','update',
                             array('error'   => & $error,
                                   'id_user' => (int)$data['id_user'],
                                   'user'    => array('logo' => $file_info['file_name'])));
   
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
        if( !isset($data['postName']) || empty($data['postName']) )
        {        
            throw new SmartModelException ('"post_name" must be defined in view class'); 
        }
        // check if array entry exists
        elseif( !isset($_FILES[$data['postName']]) )
        {
            throw new SmartModelException ('You have to select a local file to upload');
        }     
        
        if(!is_int($data['id_user']))
        {
            throw new SmartModelException('Wrong id_user format');        
        }        
        return TRUE;
    }
}

?>