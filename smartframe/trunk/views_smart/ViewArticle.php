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
 * ViewArticle class
 *
 */

class ViewArticle extends SmartView
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 300;
    
    /**
     * Execute the view of the "article" template
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

        // get article data                                                    
        $this->model->action('article','getArticle',
                             array('id_article' => (int)$this->current_id_article,
                                   'result'  => & $this->tplVar['article'],
                                   'status'  => array('=',4),
                                   'pubdate' => array('<=', 'CURRENT_TIMESTAMP'),
                                   'fields'  => array('id_article','id_node','title',
                                                      'header','overtitle',
                                                      'subtitle','body','ps') ));  

        // check if the article node has at least status 2
        $nodeStatus = $this->model->action('navigation','getNodeStatus', 
                                            array('id_node' => (int)$this->tplVar['article']['id_node']));  
                     
        if( $nodeStatus < 2 )
        {
            $this->template          = 'error'; 
            $this->tplVar['message'] = "The requested article isnt accessible";
            return;
        } 
          
        // get node title and id of the article node
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->tplVar['node'],
                                   'id_node' => (int)$this->tplVar['article']['id_node'],
                                   'fields'  => array('title','id_node','status')));                             

        // if the requested node is only available for registered users
        if( ($this->tplVar['node']['status'] == 3) && ($this->tplVar['isUserLogged'] == FALSE) )
        {
              @header('Location: '.SMART_CONTROLLER.'?view=login&url='.base64_encode('view=article&id_article='.$this->current_id_article));
              exit;
        }
 
        // get navigation node branch content of the requested article
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->tplVar['nodeBranch'],
                                   'id_node' => (int)$this->tplVar['article']['id_node'],
                                   'fields'  => array('title','id_node')));  
                                 
        // get article attached files
        $this->model->action('article','getAllFiles',
                             array('result'     => & $this->tplVar['articleFiles'],
                                   'id_article' => (int)$this->current_id_article,
                                   'order'      => 'rank',
                                   'fields'     => array('id_file','file',
                                                         'size','mime',
                                                         'title','description')) );   
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
        
        // validate id_article and view request var
        // 
        if( !isset($_GET['id_article'])   || 
                is_array($_GET['id_article']) || 
                preg_match("/[^0-9]+/",$_GET['id_article']) ) 
        {
            $this->template          = 'error';   
            $this->tplVar['message'] = "Wrong id_article value";
            $this->dontPerform = TRUE;
            return; 
        }    
        elseif( !isset($_GET['view'])     || 
                !is_string($_GET['view']) || 
                ($_GET['view'] !== 'article') ) 
        {
            $this->template          = 'error';   
            $this->tplVar['message'] = "Wrong view value";
            $this->dontPerform = TRUE;
            return;    
        }          
        else
        {
            $this->current_id_article = (int)$_GET['id_article'];         
        }
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
        $this->tplVar['nodeBranch']   = array();
        $this->tplVar['articleFiles'] = array();
        $this->tplVar['article']      = array();
        
        // template var with charset used for the html pages
        $this->tplVar['charset'] = & $this->config['charset'];
        // relative path to the smart directory
        $this->tplVar['relativePath'] = SMART_RELATIVE_PATH;
    }
}

?>