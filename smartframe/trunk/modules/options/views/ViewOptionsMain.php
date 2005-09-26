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
 * ViewOptionsMain
 *
 */
 
class ViewOptionsMain extends SmartView
{
     /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'main';
    
     /**
     * Default template folder for this view
     * @var string $templateFolder
     */    
    public  $templateFolder = 'modules/options/templates/';

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
            $this->tplVar['error'] = 'You dont have the rights to change global options!';
            $this->dontPerform = TRUE;
        }
    } 
    
    /**
     * Perform on the main view
     */
    public function perform()
    {
        if(isset($_POST['updateOptions']))
        {
            if(TRUE == $this->validatePostData())
            {
                $this->model->action( 'options','updateConfigOptions',
                                      array('fields' => &$this->fields)); 
                
                $this->model->action( 'options','deletePublicCache'); 
            }        
        }
        elseif(isset($_POST['deletePublicCache']))
        {
                $this->model->action( 'options','deletePublicCache');         
        }
        elseif(isset($_POST['optimize']))
        {
                $this->model->broadcast( 'optimizeDbTables');         
        }
        $this->setTemplateVars();
    }  
    
    /**
     * Set template variables
     */
    private function setTemplateVars()
    {
        $this->tplVar['publicTplFolder']      = $this->config['templates_folder'];
        $this->tplVar['publicViewFolder']     = $this->config['views_folder'];
        $this->tplVar['allPublicViewFolders'] = $this->getPublicFolders( 'views_' );
        $this->tplVar['allPublicTplFolders']  = $this->getPublicFolders( 'templates_' );
        $this->tplVar['rejectedFiles']        = $this->config['rejected_files'];

        $this->tplVar['disableCache']         = $this->config['disable_cache'];
    } 
    
    /**
     * Get all public views/templates folders
     */
    private function getPublicFolders( $folder_prefix )
    {
        $folders = array();
        $main_dir = SMART_BASE_DIR ;
        
        if($folder_prefix == 'templates_')
        {
            $folders[] = '';
        }
          
        if ( (($handle = @opendir( $main_dir ))) != FALSE )
        {
            while ( (( $_dir = readdir( $handle ) )) != false )
            {
                if ( ( $_dir == "." ) || ( $_dir == ".." ) )
                {
                    continue;
                }
                $match_str = "/^{$folder_prefix}/";
                if(is_dir($_dir) && preg_match("$match_str", $_dir))
                {
                    $folders[] = $_dir . '/';
                }
            }
            @closedir( $handle );
        }
        else
        {
            trigger_error( "Can not open folder to read: ".$main_dir, E_USER_ERROR  );
        }
        
        sort( $folders );
        return $folders;
    } 
    
   /**
    * Validate form data
    *
    */    
    private function validatePostData()
    {
        $this->fields  = array();

        if(isset($_POST['templates_folder']))
        {
            $this->fields['templates_folder'] = (string)$_POST['templates_folder'];
            $this->config['templates_folder'] = (string)$_POST['templates_folder'];
        }  

        if(isset($_POST['views_folder']) && !empty($_POST['views_folder']))
        {
            if(preg_match("/^views_[a-zA-Z0-9_-]+/",$_POST['views_folder']) )
            {
                $this->fields['views_folder'] = (string)$_POST['views_folder'];
                $this->config['views_folder'] = (string)$_POST['views_folder'];
            }
        }  
        
       if(isset($_POST['disable_cache']) && $_POST['disable_cache'] == '1')
       {
            $this->fields['disable_cache'] = 1;
            $this->config['disable_cache'] = 1;
       }
       else
       {
            $this->fields['disable_cache'] = 0;
            $this->config['disable_cache'] = 0;
       }

       $this->fields['rejected_files'] = (string)$_POST['rejected_files'];
       $this->config['rejected_files'] = (string)$_POST['rejected_files'];
        
        return TRUE;
    }    
}

?>