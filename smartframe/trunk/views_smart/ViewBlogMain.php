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
 * ViewNode class
 *
 */

class ViewBlogMain extends SmartView
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 0;
    
    /**
     * Execute the view of the "node" template
     */
    function perform()
    { 
        // init variables (see private function below)
        $this->initVars();
        
        // dont proceed if an error occure
        if(isset( $this->dontPerform ))
        {
            return;
        }

         // get requested node content
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->tplVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'status'  => array('>=',2),
                                   'fields'  => array('title')));

        // get navigation node branch content of the requested node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->tplVar['nodeBranch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'fields'  => array('title','id_node')));  
         
        // get child nodes content of the requested node
        // only with status=2, means active      
        $this->model->action('navigation','getChilds', 
                             array('result'  => & $this->tplVar['childNodes'],
                                   'id_node' => (int)$this->current_id_node,
                                   'status'  => array('>=',2),
                                   'fields'  => array('title','id_node'))); 

        // id_node 53 is the top level node "Blog" means sector
        // here we show all articles of this sector and all sub categories
        // 
        if($this->current_id_node == 53)
        {
            $id_item = 'id_sector';
        }
        else
        {
            $id_item = 'id_node';
        }

        // get node related article titles count by 10                                                     
        $this->model->action('article','getArticles', 
                              array($id_item  => array((int)$this->current_id_node),
                                    'result'  => & $this->tplVar['allArticles'],
                                    'status'  => array('>=', 4),
                                    'pubdate' => array('<=', 'CURRENT_TIMESTAMP'),
                                    'order'   => array('pubdate', 'desc'),
                                    'limit'   => array('perPage' => (int)$this->articlesPerPage,
                                                       'numPage' => (int)$this->pageNumber),
                                    'fields'  => array('id_article','title','body',
                                                       'id_node','pubdate', 'num_comments') ) );
        
        // get node titles of each article
        $this->getArticlesNodes();

        // create article pager links
        $this->model->action('article','pager', 
                             array('result'     => & $this->tplVar['pager'],
                                   $id_item     => array((int)$this->current_id_node),
                                   'status'     => array('>=', '4'),
                                   'pubdate'    => array('<=', 'CURRENT_TIMESTAMP'),
                                   'perPage'    => $this->articlesPerPage,
                                   'numPage'    => (int)$this->pageNumber,
                                   'delta'      => 10,
                                   'url'        => SMART_CONTROLLER.'?id_node='.$this->current_id_node,
                                   'var_prefix' => 'article_',
                                   'css_class'  => 'smart_pager'));  
        
        // build rss file
        $this->rssBuilder();
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
        
        // fetch the current id_node. If no id_node defined or not numeric
        // this view class loads the error template
        if( !isset($_REQUEST['id_node']) || is_array($_REQUEST['id_node']) || preg_match("/[^0-9]+/",$_REQUEST['id_node']) ) 
        {
            $this->template          = 'error';  
            $this->tplVar['message'] = "Wrong id_node value";
            $this->dontPerform       = TRUE;
            return;
        }
        else
        {
            $this->current_id_node    = (int)$_REQUEST['id_node'];          
        }
        
        // check if the demanded node has at least status 2
        $nodeStatus = $this->model->action('navigation','getNodeStatus', 
                                            array('id_node' => (int)$this->current_id_node));  
        
        // if the requested node isnt active
        if( $nodeStatus < 2 )
        {
            $this->template          = 'error'; 
            $this->tplVar['message'] = "The requested node isnt accessible";
            $this->dontPerform       = TRUE;
            // disable caching
            $this->cacheExpire = 0;
        } 
        // if the requested node is only available for registered users
        elseif( ($nodeStatus == 3) && ($this->tplVar['isUserLogged'] == FALSE) )
        {
              // set url vars to come back to this page after login
              $this->model->session->set('url','id_node='.$this->current_id_node);
              // switch to the login page
              @header('Location: '.SMART_CONTROLLER.'?view=login');
              exit;
        }
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
        $this->tplVar['allArticles']  = array();
        $this->tplVar['links']        = array();
        $this->tplVar['pager']        = '';

        // set articles limit per page
        $this->articlesPerPage = 20;
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
        // relative path to the smart directory
        $this->tplVar['relativePath'] = SMART_RELATIVE_PATH;
        
        // init template variable for keyword related links
        $this->tplVar['keywordLink'] = array();   

        // we need this template vars to show admin links if the user is logged
        $this->tplVar['loggedUserRole']      = $this->viewVar['loggedUserRole'];
        $this->tplVar['adminWebController']  = $this->config['admin_web_controller'];        
    }

    /**
     * get node title of each article
     *
     */ 
    private function getArticlesNodes()
    {
        foreach($this->tplVar['allArticles'] as & $article)
        {
            $article['node'] = array();
            // get requested node content
            $this->model->action('navigation','getNode', 
                                  array('result'  => & $article['node'],
                                        'id_node' => (int)$article['id_node'],
                                        'fields'  => array('title')));
        }
    }

    /**
     * Build rss file with article titles of the current node
     *
     */     
    private function rssBuilder()
    {
        $this->tplVar['node']['rssfile'] = '';
        
        $this->model->action('article','feedCreator',
               array('format'       => 'rss',
                     'output'       => 'save',
                     'id'           => 'blog',
                     'items'        => & $this->tplVar['allArticles'],
                     'rssfile'      => & $this->tplVar['node']['rssfile'],
                     'expire'       => 3600,
                     'channel' => array('about'    => 'http://www.smart3.org',
                                        'link'     => 'http://www.smart3.org',
                                        'desc'     => 'test',
                                        'title'    => 'Smart3 php5 framework - BLOG'),
                     'baseUrl'    => 'http://www.smart3.org/index.php?id_article='
                     ) );
                                                       
    }
}

?>