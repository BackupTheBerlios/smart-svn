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
 * ViewArticleOptions
 *
 */
 
class ViewArticleOptions extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'options';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public  $templateFolder = 'modules/article/templates/';
    
    /**
     * Submited config options data array
     */
    private $fields = array();

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // if no rights for the logged user, show error template
        // only administrators can change options
        if($this->viewVar['loggedUserRole'] > 20)
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->tplVar['error'] = 'You have not the rights to change navigation module options!';
            $this->dontPerform = TRUE;
        }
    } 
    
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {   
        $this->tplVar['error'] = FALSE;
        
        if(isset($_POST['updateOptions']))
        {
            if(TRUE == $this->validatePostData())
            {
                $this->model->action( 'article','updateConfigOptions',
                                      array('fields' => &$this->fields)); 
            }
        }

        // get all available public views
        $this->tplVar['option'] = array();
        $this->model->action( 'article','getAllConfigOptions',
                              array('result' => &$this->tplVar['option']) );   
                                           
        return TRUE;
    }   
   /**
    * Validate form data
    *
    */    
    private function validatePostData()
    {
        $this->tplVar['error'] = array();
        $this->fields  = array();
        
        if(isset($_POST['thumb_width']) && !empty($_POST['thumb_width']))
        {
            if(($_POST['thumb_width'] > 10) && ($_POST['thumb_width'] <= 250))
            {
                $this->fields['thumb_width'] = (int)$_POST['thumb_width'];
            }
            else
            {
                $this->tplVar['error'][] = "Thumbnail width must be between 10 and 250!";
            }
        }
        else
        {
            $this->tplVar['error'][] = "Thumbnail width field is empty!";
        }   
        
        if(isset($_POST['img_size_max']) && !empty($_POST['img_size_max']))
        {
            if(($_POST['img_size_max'] > 0) && ($_POST['img_size_max'] <= 3000000))
            {
                $this->fields['img_size_max'] = (int)$_POST['img_size_max'];
            }
            else
            {
                $this->tplVar['error'][] = "Image file size must be between 100 and 3000000!";
            }
        }
        else
        {
            $this->tplVar['error'][] = "Image file size field is empty!";
        }  
        
        if(isset($_POST['file_size_max']) && !empty($_POST['file_size_max']))
        {
            if(($_POST['file_size_max'] > 0) && ($_POST['file_size_max'] <= 8000000))
            {
                $this->fields['file_size_max'] = (int)$_POST['file_size_max'];
            }
            else
            {
                $this->tplVar['error'][] = "File size must be between 100 and 3000000!";
            }
        }
        else
        {
            $this->tplVar['error'][] = "File size field is empty!";
        }   
        
        if(isset($_POST['force_format']) && !empty($_POST['force_format']))
        {
            if(($_POST['force_format'] >= 0) && ($_POST['force_format'] <= 2))
            {
                $this->fields['force_format'] = (int)$_POST['force_format'];
            }
        } 
        
        if(isset($_POST['default_format']) && !empty($_POST['default_format']))
        {
            if(($_POST['default_format'] >= 0) && ($_POST['default_format'] <= 2))
            {
                $this->fields['default_format'] = (int)$_POST['default_format'];
            }
        }  
        
        if(isset($_POST['use_overtitle']) && ($_POST['use_overtitle'] == '1'))
        {
            $this->fields['use_overtitle'] = (int)$_POST['use_overtitle'];
        }
        else
        {
            $this->fields['use_overtitle'] = 0;
        }
        
        if(isset($_POST['use_subtitle']) && ($_POST['use_subtitle'] == '1'))
        {
            $this->fields['use_subtitle'] = (int)$_POST['use_subtitle'];
        } 
        else
        {
            $this->fields['use_subtitle'] = 0;
        }
        
        if(isset($_POST['use_description']) && ($_POST['use_description'] == '1'))
        {
            $this->fields['use_description'] = (int)$_POST['use_description'];
        }
        else
        {
            $this->fields['use_description'] = 0;
        }
        
        if(isset($_POST['use_header']) && ($_POST['use_header'] == '1'))
        {
            $this->fields['use_header'] = (int)$_POST['use_header'];
        } 
        else
        {
            $this->fields['use_header'] = 0;
        }  
        
        if(isset($_POST['use_ps']) && ($_POST['use_ps'] == '1'))
        {
            $this->fields['use_ps'] = (int)$_POST['use_ps'];
        } 
        else
        {
            $this->fields['use_ps'] = 0;
        }         

        if(isset($_POST['use_changedate']) && ($_POST['use_changedate'] == '1'))
        {
            $this->fields['use_changedate'] = (int)$_POST['use_changedate'];
        } 
        else
        {
            $this->fields['use_changedate'] = 0;
        }  
        
        if(isset($_POST['use_articledate']) && ($_POST['use_articledate'] == '1'))
        {
            $this->fields['use_articledate'] = (int)$_POST['use_articledate'];
        } 
        else
        {
            $this->fields['use_articledate'] = 0;
        }         
        
        if(isset($_POST['use_logo']) && ($_POST['use_logo'] == '1'))
        {
            $this->fields['use_logo'] = (int)$_POST['use_logo'];
        } 
        else
        {
            $this->fields['use_logo'] = 0;
        }        
        
        if(isset($_POST['use_images']) && ($_POST['use_images'] == '1'))
        {
            $this->fields['use_images'] = (int)$_POST['use_images'];
        } 
        else
        {
            $this->fields['use_images'] = 0;
        }
        
        if(isset($_POST['use_files']) && ($_POST['use_files'] == '1'))
        {
            $this->fields['use_files'] = (int)$_POST['use_files'];
        } 
        else
        {
            $this->fields['use_files'] = (int)$_POST['use_files'];
        }
        
        if(count($this->tplVar['error']) > 0)
        {
            return FALSE;
        }
        
        
        if(count($this->tplVar['error'])>0)
        {
            return FALSE;
        }
        
        return TRUE;
    }
}

?>
