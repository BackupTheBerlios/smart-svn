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
 * ViewSearch class
 *
 */

class ViewSearch extends SmartView
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Execute the view of the "search" template
     */
    function perform()
    { 
        // init variables (see private function below)
        $this->initVars();
          
        // search articles                                                   
        $this->model->action('article','search',
                             array('result'  => & $this->tplVar['articles'], 
                                   'search'  => (string)$this->searchString,
                                   'status'  => array('=',4),
                                   'fields'  => array('id_article','title') ));  

        // get node + node branch of each article
        foreach($this->tplVar['articles'] as & $article)
        {
            $article['nodeBranch'] = array();
            $article['node']       = array();
            
            // get navigation node branch content of the article node
            $this->model->action('navigation','getBranch', 
                             array('result'  => & $article['nodeBranch'],
                                   'id_node' => (int)$article['id_node'],
                                   'fields'  => array('title','id_node','id_parent')));   
                                   
            // get article node content
            $this->model->action('navigation','getNode', 
                                 array('result'  => & $article['node'],
                                       'id_node' => (int)$article['id_node'],
                                       'fields'  => array('title','id_node')));                             
                                   
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
        if( isset($_REQUEST['search']) )
        {
            $this->searchString = SmartCommonUtil::stripSlashes((string)$_REQUEST['search']);
        }
        else
        {
            $this->searchString = '';
        }
        
        // assign template variable with search string
        $this->tplVar['search'] = & $this->searchString;
        
        // template array variables
        $this->tplVar['articles'] = array();
        
        // template var with charset used for the html pages
        $this->tplVar['charset'] = & $this->config['charset'];
    }
}

?>