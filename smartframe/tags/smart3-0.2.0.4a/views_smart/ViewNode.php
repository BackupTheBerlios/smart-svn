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
 * ViewNode class
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

class ViewNode extends SmartView
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Execute the view of the "node" template
     */
    function perform()
    { 
        // init variables (see private function below)
        $this->initVars();
          
        // get requested node content
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->tplVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'fields'  => array('title','body','id_node','media_folder')));                             

        // get child nodes content of the requested node
        // only with status=2, means active      
        $this->model->action('navigation','getChilds', 
                             array('result'  => & $this->tplVar['childNodes'],
                                   'id_node' => (int)$this->current_id_node,
                                   'status'  => array('=',2),
                                   'fields'  => array('title','short_text','id_node')));
 
        // get navigation node branch content of the requested node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->tplVar['nodeBranch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'fields'  => array('title','id_node')));  
                                 
        // get node attached files
        $this->model->action('navigation','getAllFiles',
                             array('result'  => & $this->tplVar['nodeFiles'],
                                   'id_node' => (int)$this->current_id_node,
                                   'order'   => 'rank',
                                   'fields'  => array('id_file',
                                                      'file',
                                                      'size',
                                                      'mime',
                                                      'title',
                                                      'description')) );   

        // get node related article titles count by 10                                                     
        $this->model->action('article','getNodeArticles',
                             array('id_node' => (int)$this->current_id_node,
                                   'result'  => & $this->tplVar['nodeArticles'],
                                   'status'  => array('=',4),
                                   'order'   => array('rank', 'asc'),
                                   'limit'   => array('perPage' => $this->articlesPerPage,
                                                      'numPage' => (int)$this->pageNumber),
                                   'fields'  => array('id_article','title') ));

        // get node related links
        $this->model->action('link','getLinks', 
                             array('result'  => & $this->tplVar['links'],
                                   'id_node' => (int)$this->current_id_node,
                                   'status'  => array('=','2'),
                                   'fields'  => array('title','url','id_link',
                                                      'description')));   

        // create article pager links
        $this->model->action('article','pager', 
                             array('result'     => & $this->tplVar['pager'],
                                   'id_node'    => (int)$this->current_id_node,
                                   'status'     => array('=','4'),
                                   'perPage'    => $this->articlesPerPage,
                                   'numPage'    => (int)$this->pageNumber,
                                   'delta'      => 5,
                                   'url'        => SMART_CONTROLLER.'?id_node='.$this->current_id_node,
                                   'var_prefix' => 'article_',
                                   'css_class'  => 'smart_pager'));  
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
        if( !isset($_REQUEST['id_node']) || is_array($_REQUEST['id_node']) || preg_match("/[^0-9]+/",$_REQUEST['id_node']) ) 
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
        $this->tplVar['node']         = array();
        $this->tplVar['childNodes']   = array();
        $this->tplVar['nodeBranch']   = array();
        $this->tplVar['nodeFiles']    = array();
        $this->tplVar['nodeArticles'] = array();
        $this->tplVar['links']        = array();
        $this->tplVar['pager']        = '';

        // set articles limit per page
        $this->articlesPerPage = 10;
        // get current article pager page
        if(!isset($_GET['article_page']))
        {
            $this->pageNumber = 1;
        }
        else
        {
            $this->pageNumber = (int)$_GET['article_page'];
        }
        
        // template var with charset used for the html pages
        $this->tplVar['charset'] = & $this->config['charset'];
    }
}

?>