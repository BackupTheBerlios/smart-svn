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
 * view_navigation_media_manager class of the template "tpl.navigation_media_manager.php"
 *
 */
 
class view_navigation_media_manager extends view
{
   /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'navigation_media_manager';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/navigation/templates/';
        
   /**
     * Execute the view of the template "tpl.navigation_media_manager.php"
     *
     * @return bool true
     */
    function perform()
    {
        if( isset($_POST['upload']) )
        {
            M( MOD_NAVIGATION,
               'upload_media_file',
               array('media_file'   => $_FILES['file'],
                     'media_folder' => '',
                     'error'        => 'tpl_error'));        
        }
        elseif( isset($_POST['delete_file']) )
        {
            M( MOD_NAVIGATION,
               'delete_media_file',
               array('media_file'   => $_POST['media_file'],
                     'media_folder' => '',
                     'error'        => 'tpl_error'));        
        } 
        
        M( MOD_NAVIGATION,
           'get_media_files',
           array('result'       => 'tpl_media_files',
                 'media_folder' => ''));
           
        return TRUE;
    }   
}

?>