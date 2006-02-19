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
 * ActionArticleUploadLogo class 
 *
 */
include_once(SMART_BASE_DIR . 'modules/article/includes/ActionArticleFileUploadBase.php');

class ActionArticleUploadLogo extends ActionArticleFileUploadBase
{
    /**
     * add article logo picture
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $media_folder = $this->getArticleMediaFolder( $data['id_article'] );
        
        $file_info = $this->getUniqueMediaFileName($media_folder, $_FILES[$data['postName']]['name']);

        if(FALSE == $this->moveUploadedFile($_FILES[$data['postName']]['tmp_name'], $file_info['file_path']))
        { 
            throw new SmartModelException ('Cant upload file');   
        }
        
        $this->model->action('article','updateArticle',
                             array('error'      => & $data['error'],
                                   'id_article' => $data['id_article'],
                                   'fields'     => array('logo' => $file_info['file_name'])));

           
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
        if(!isset($data['error']))
        {
            throw new SmartModelException("'error' var isnt set!");
        }
        elseif(!is_array($data['error']))
        {
            throw new SmartModelException("'error' var isnt from type array!");
        }    

        // validate logo upload file name
        if( !isset($data['postName']) || empty($data['postName']) )
        {        
            throw new SmartModelException ('"post_name" must be defined in view class'); 
        }  
        elseif( !file_exists($_FILES[$data['postName']]['tmp_name']) )
        {
            $data['error'][] = 'File upload failed';
        }  

        if(FALSE == $this->isAllowedExtension( $data ))
        {
            $data['error'][] = 'This file type isnt allowed to upload';
        } 
        
        if(!isset($data['id_article']))
        {
            throw new SmartModelException("No 'id_article' defined. Required!");
        }        
        if(!is_int($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt from type int');        
        }        

        if(count($data['error']) > 0)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * check if the file type to upload is allowed
     *
     * @param param $array
     * @return bool
     */       
    private function isAllowedExtension( &$data )
    {
        if(preg_match("/\.(gif|jpg|png)$/i",$_FILES[$data['postName']]['name']))
        {
            return TRUE;
        }
        
        return FALSE;
    }        
}

?>