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
 * ActionNavigationUploadLogo class 
 *
 */
include_once(SMART_BASE_DIR . 'modules/navigation/includes/ActionNavigationFileUploadBase.php');

class ActionNavigationUploadLogo extends ActionNavigationFileUploadBase
{
    /**
     * add user logo picture
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $media_folder = $this->getNodeMediaFolder( $data['id_node'] );
        
        $file_info = $this->getUniqueMediaFileName($media_folder, $_FILES[$data['postName']]['name']);

        if(FALSE == $this->moveUploadedFile($_FILES[$data['postName']]['tmp_name'], $file_info['file_path']))
        { 
            throw new SmartModelException ('Cant upload file');   
        }
        
        $this->model->action('navigation',
                             'updateNode',
                             array('error'   => & $data['error'],
                                   'id_node' => $data['id_node'],
                                   'fields'  => array('logo' => $file_info['file_name'])));

           
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
        if(!isset($data['id_node']))
        {
            throw new SmartModelException("No 'id_node' defined. Required!");
        }        
        if(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }        
        return TRUE;
    }
}

?>