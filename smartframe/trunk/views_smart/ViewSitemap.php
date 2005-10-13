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
 * ViewSitemap class
 *
 * The parent variables are:
 * $tplVar  - Array that may contains template variables
 * $viewVar - Array that may contains view variables, which
 *            are needed by some followed nested views.
 * $model   - The model object
 *            We need it to call modules actions
 * $template - Here you can define an other template name as the default
 * $renderTemplate - Is there a template associated with this view?
 *                   SMART_TEMPLATE_RENDER or SMART_TEMPLATE_RENDER_NONE
 * $viewData - Data passed to this view by the caller
 * $cacheExpire - Expire time in seconds of the cache for this view. 0 means cache disabled
 */

class ViewSitemap extends SmartView
{
    /**
     * Cache expire time in seconds
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Execute the view of the "sitemap" template
     */
    function perform()
    {     
        $this->initVars();

        // get node content
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->tplVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'fields'  => array('title','body','id_node'))); 
        
        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->tplVar['tree'],
                                   'status'  => array('>=', 2),
                                   'fields'  => array('id_parent','status','id_node','title')));   

        // get for each node the related articles
        foreach($this->tplVar['tree'] as & $node)
        {
            $node['article'] = array();                                                   
            $this->model->action('article','getNodeArticles',
                                 array('id_node' => (int)$node['id_node'],
                                       'result'  => & $node['article'],
                                       'status'  => array('>=',4),
                                       'order'   => array('rank', 'asc'),
                                       'fields'  => array('id_article','title') ));                     
        }
    }

    /**
     * authentication
     *
     */
    public function auth()
    {
        // Check if the visitor is a logged user
        //
        if(NULL == ($this->viewVar['loggedUserId'] = $this->model->session->get('loggedUserId')))
        {
            $this->tplVar['isUserLogged'] = FALSE; 
        }
        else
        {
            $this->tplVar['isUserLogged'] = TRUE;
        }
        $this->viewVar['loggedUserRole'] = $this->model->session->get('loggedUserRole');     
    }

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // fetch the current id_node. If no id_node defined or not numeric
        // this view class loads the error template
        if( !isset($_REQUEST['id_node']) || preg_match("/[^0-9]+/",$_REQUEST['id_node']) ) 
        {
            $this->template  = 'error';     
        }
        else
        {
            $this->current_id_node    = (int)$_REQUEST['id_node'];          
        }
        
        // filter action of the common module to prevent browser caching
        $this->model->action( 'common', 'filterDisableBrowserCache');    
    }

    /**
     * append filter chain
     *
     */
    public function appendFilterChain( & $outputBuffer )
    {
        // filter action of the common module that trims the html output
        $this->model->action( 'common', 'filterTrim', array('str' => & $outputBuffer) );        
    }

    /**
     * init some variables
     *
     */    
    private function initVars()
    {
        // template array variables
        $this->tplVar['tree'] = array();
        $this->tplVar['node'] = array();
        
        // template var with charset used for the html pages
        $this->tplVar['charset'] = & $this->config['charset'];
        // relative path to the smart directory
        $this->tplVar['relativePath'] = SMART_RELATIVE_PATH;
        
        // we need this template vars to show admin links if the user is logged
        $this->tplVar['loggedUserRole']      = $this->viewVar['loggedUserRole'];
        $this->tplVar['adminWebController']  = $this->config['admin_web_controller']; 
    }
}

?>