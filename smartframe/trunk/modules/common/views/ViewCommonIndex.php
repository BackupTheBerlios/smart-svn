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
 * ViewCommonIndex class
 *
 */

class ViewCommonIndex extends SmartView
{
     /**
     * Login Module to load
     * @var mixed $loginModule
     */
    private $loginModule = FALSE;

     /**
     * Login View to load
     * @var mixed $loginView
     */
    private $loginView = FALSE;
    
     /**
     * Default template for this view
     * @var string $template
     */
    public $template = 'index';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/common/templates/';
    
    /**
     * Execute the main view
     *
     */
    public function perform()
    {
        // Set the module which takes the login part
        if($this->loginModule != FALSE)
        {
            $module = $this->loginModule; 
        }
        // if no request set default module
        elseif(!isset($_REQUEST['mod']))
        {
            $module = $this->config['default_module'];    
        }
        else
        {
            $module = $_REQUEST['mod'];    
        }

        // Set the view which takes the login part
        if($this->loginView != FALSE)
        {
            $view = $this->loginView; 
        }        
        // if no request set default view
        elseif(!isset($_REQUEST['view']))
        {
            $view = 'main';    
        }    
        else
        {
            $view = $_REQUEST['view'];    
        }
        
        // disable main menu ?
        if(isset($_REQUEST['disableMainMenu']))
        {
            $this->tplVar['disableMainMenu'] = TRUE;   
        } 
        else
        {
            $this->tplVar['disableMainMenu'] = FALSE;   
        }
        
        // assign the template root view variable
        $this->tplVar['moduleRootView'] = ucfirst($module).'Index';
        
        // assign the template child view variable
        $this->tplVar['moduleChildView'] = ucfirst($module).ucfirst($view);
       
        // validate module root view name
        $this->validateViewName( $this->tplVar['moduleRootView'], $module, 'index' ); 
        
        // validate module child view name
        $this->validateViewName( $this->tplVar['moduleChildView'], $module, $view ); 
        
        // assign some template variables
        $this->tplVar['smartVersionNumber'] = $this->config['smart_version'];
        $this->tplVar['requestedModule'] = $module;
        $this->tplVar['moduleList'] = $this->model->getModuleInfo();
        $this->tplVar['charset']    = $this->config['charset'];
        $this->tplVar['textarea_rows'] = $this->config['textarea_rows'];
        $this->tplVar['publicWebController'] = $this->config['public_web_controller'];
        $this->tplVar['adminWebController']  = $this->config['admin_web_controller'];
        
        // assign template var to show the admin header and footer
        // some views dosent need it
        if(isset($_REQUEST['nodecoration']))
        {
            $this->tplVar['showHeaderFooter'] = FALSE;
        }
        else
        {
            $this->tplVar['showHeaderFooter'] = TRUE;
        }       
    }  

    /**
     * Validate view request name.
     *
     * @see dispatch() 
     */
    private function validateViewName( $moduleView, $module, $view )
    {
        if(preg_match("/[^a-zA-Z0-9_]/", $moduleView))
        {
            throw new SmartViewException('Wrong view fromat: ' . $moduleView);
        }

        if(!@file_exists(SMART_BASE_DIR . '/modules/' . $module . '/views/View' . $moduleView . '.php'))
        {
            throw new SmartViewException('View dosent exists: ' . SMART_BASE_DIR . '/modules/' . $module . '/views/View' . $moduleView . '.php');
        }
    }
    
    /**
     * authentication
     *
     */
    public function auth()
    {
        // if both variables contain NULL, means that the user isnt authenticated.
        // the prependFilterChain() function check the permission
        $this->viewVar['loggedUserId']   = $this->model->session->get('loggedUserId');
        $this->viewVar['loggedUserRole'] = $this->model->session->get('loggedUserRole');
        $this->viewVar['loggedUserGmt']  = $this->model->session->get('loggedUserGmt');
    }
 
    /**
     * Execute Prepended filter chain
     *
     */
    public function prependFilterChain()
    {
        $this->checkPermission();
    } 

    /**
     * Check permission to access the admin section
     *
     */
    private function checkPermission()
    {
        // if login user id dosent exists set login target
        if($this->viewVar['loggedUserId'] === NULL)
        {
            $this->setLoginTarget();
        }
        
        // User Role flags
        // Admin  = 20
        // Editor = 40
        // Webuser = 100
        //
        // Webuser (100) hasnt access to the admin section
        //
        if(($this->viewVar['loggedUserRole'] === NULL) || 
           ($this->viewVar['loggedUserRole'] > 40))
        {
            $this->setLoginTarget();
        }
        else
        {
            // set template variable
            $this->tplVar['isUserLogged'] = TRUE;
            $this->tplVar['userRole'] = $this->viewVar['loggedUserRole'];
        }    
    }

    /**
     * Set login module name and view name
     *
     */    
    private function setLoginTarget()
    {
        $this->loginModule = 'user';
        $this->loginView   = 'login';
        // set template variable
        $this->tplVar['isUserLogged'] = FALSE;
    }
}

?>