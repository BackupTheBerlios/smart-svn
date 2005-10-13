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
 * ViewSearch class
 *
 */

class ViewSearch extends SmartView
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 300;
    
    /**
     * Execute the view of the "search" template
     */
    function perform()
    { 
        // init variables (see private function below)
        $this->initVars();
          
        // search articles                                                   
        $this->model->action('article','search',
                             array('result'     => & $this->tplVar['articles'], 
                                   'search'     => (string)$this->searchString,
                                   'status'     => array('=', 4),
                                   'nodeStatus' => array('>=', 2),
                                   'pubdate' => array('<=', 'CURRENT_TIMESTAMP'),
                                   'limit'   => array('perPage' => $this->articlesPerPage,
                                                      'numPage' => (int)$this->pageNumber),                                   
                                   'fields'  => array('id_article','title','id_node') ));  

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
        
        // create article pager links
        $this->model->action('article','pager', 
                             array('result'     => & $this->tplVar['pager'],
                                   'search'     => (string)$this->searchString,
                                   'status'     => array('=','4'),
                                   'nodeStatus' => array('>=', 2),
                                   'pubdate'    => array('<=', 'CURRENT_TIMESTAMP'),
                                   'perPage'    => $this->articlesPerPage,
                                   'numPage'    => (int)$this->pageNumber,
                                   'delta'      => 10,
                                   'url'        => SMART_CONTROLLER.'?view=search&search='.$this->pagerUrlSearchString,
                                   'var_prefix' => 'search_',
                                   'css_class'  => 'search_pager'));          
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
        if( isset($_POST['search']) )
        {
            $this->searchString = SmartCommonUtil::stripSlashes((string)$_POST['search']);
            $this->pagerUrlSearchString = urlencode(SmartCommonUtil::stripSlashes((string)$_POST['search']));
        }
        elseif( isset($_GET['search']) )
        {
            $this->searchString = urldecode(SmartCommonUtil::stripSlashes((string)$_GET['search']));
            $this->pagerUrlSearchString = SmartCommonUtil::stripSlashes((string)$_GET['search']);
        }        
        else
        {
            $this->searchString = '';
            $this->pagerUrlSearchString = '';
        }
        
        // assign template variable with search string
        $this->tplVar['search']     = & $this->searchString;
        $this->tplVar['formsearch'] = htmlspecialchars($this->searchString);
        
        // template array variables
        $this->tplVar['articles'] = array();
        $this->tplVar['pager']    = '';
        
        // set articles limit per page
        $this->articlesPerPage = 10;
        
        // get current article pager page
        if(!isset($_GET['search_page']))
        {
            $this->pageNumber = 1;
        }
        else
        {
            $this->pageNumber = (int)$_GET['search_page'];
        }
        
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