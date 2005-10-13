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
 * ViewUserOptions class
 *
 */

class ViewUserOptions extends SmartView
{
     /**
     * Template for this view
     * @var string $template
     */
    public $template = 'options';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/user/templates/';
    
    /**
     * Execute the view
     *
     */
    public function perform()
    {
        $this->tplVar['uptodate'] = FALSE;
        
        if(isset($_POST['updateoptions']))
        {
            // update user module options
            $this->model->action('user','updateOptions',
                                 array('file_size_max'  => (int)$_POST['file_size_max'],
                                       'img_size_max'   => (int)$_POST['img_size_max'],
                                       'force_format'   => (int)$_POST['force_format'],
                                       'default_format' => (int)$_POST['default_format'],
                                       'thumb_width'    => (int)$_POST['thumb_width']));  

            $this->tplVar['uptodate'] = TRUE;                           
        }

        // init users template variable 
        $this->tplVar['option'] = array();
        
        // assign template variable with options of the user module
        $this->model->action('user','getOptions',
                             array('result' => & $this->tplVar['option']));  
    
    }  
}

?>