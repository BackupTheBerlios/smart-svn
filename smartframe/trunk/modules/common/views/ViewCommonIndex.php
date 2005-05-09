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
 * ViewCommonIndex class
 *
 */

class ViewCommonIndex extends SmartView
{
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
     * Execute the view
     *
     */
    function perform()
    {
        // set default module
        if(!isset($_REQUEST['module']))
        {
            $module = $this->config['default_module'];    
        }
        // set default view
        if(!isset($_REQUEST['view']))
        {
            $view = 'index';    
        }    
        
        // build module view name
        $this->tplVar['view'] = ucfirst($module).ucfirst($view);
       
        // validate module view name
        $this->validateViewName( $this->tplVar['view'], $module, $view ); 
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
    function auth()
    {

    }
        
}

?>