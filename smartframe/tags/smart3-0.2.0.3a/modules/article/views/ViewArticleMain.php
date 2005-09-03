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
 * ViewArticleMain
 *
 */
 
class ViewArticleMain extends SmartView
{
   /**
     * template for this view
     * @var string $template
     */
    public $template = 'main';
    
   /**
     * template folder for this view
     * @var string $template_folder
     */    
    public $templateFolder = 'modules/article/templates/';
    
   /**
     * current id_node
     * @var int $current_id_node
     */
    private $current_id_node;    
        
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        // init variables for this view
        $this->initVars();

        // move up or down a node
        if( isset($_GET['id_article_up']) && 
            !preg_match("/[^0-9]+/",$_GET['id_article_up']) &&
            ($this->allowModify() == TRUE) )
        {
            $this->model->action('article','moveArticleRank', 
                                 array('id_article' => (int)$_GET['id_article_up'],
                                       'id_node'    => (int)$_GET['id_node'],
                                       'dir'        => 'up'));        
        }
        elseif( isset($_GET['id_article_down']) && 
                !preg_match("/[^0-9]+/",$_GET['id_article_down']) &&
                ($this->allowModify() == TRUE) )
        {
            $this->model->action('article','moveArticleRank', 
                                 array('id_article' => (int)$_GET['id_article_down'],
                                       'id_node'    => (int)$_GET['id_node'],
                                       'dir'        => 'down'));        
        }
        
        // get current node data if we arent at the top level node
        if($this->current_id_node != 0)
        {
            $this->model->action('navigation','getNode', 
                                 array('result'  => & $this->tplVar['node'],
                                       'id_node' => (int)$this->current_id_node,
                                       'error'   => & $this->tplVar['error'],
                                       'fields'  => array('title','id_node')));        
        }
    
        // get child navigation nodes
        $this->model->action('navigation','getChilds', 
                             array('result'  => & $this->tplVar['nodes'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node','id_parent','status')));
    
        // get navigation node branch of the current node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->tplVar['branch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node')));  
                                   
        // get node related articles
        $this->model->action('article','getNodeArticles', 
                             array('result'  => & $this->tplVar['articles'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'order'   => array('rank','asc'),
                                   'fields'  => array('title','id_article','status')));                                   

        // get article locks
        $this->getLocks();
    }  
    
     /**
     * assign template variables with lock status of each article
     *
     */   
    private function getLocks()
    {
        $row = 0;
        
        foreach($this->tplVar['articles'] as $article)
        {
            // lock the user to edit
            $result = $this->model->action('article','lock',
                                     array('job'        => 'is_locked',
                                           'id_article' => (int)$article['id_article'],
                                           'by_id_user' => (int)$this->viewVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->tplVar['articles'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->tplVar['articles'][$row]['lock'] = FALSE;  
            }
            
            $row++;
        }    
    }   
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        // fetch the current id_node. If no node the script assums that
        // we are at the top level with id_parent 0
        if( !isset($_REQUEST['id_node']) || preg_match("/[^0-9]+/",$_REQUEST['id_node']) ) 
        {
            $this->tplVar['id_node']  = 0;
            $this->current_id_node    = 0;      
        }
        else
        {
            $this->tplVar['id_node']  = (int)$_REQUEST['id_node'];
            $this->current_id_node    = (int)$_REQUEST['id_node'];          
        }

        // set template variable to show edit links        
        $this->tplVar['showArticle'] = $this->allowModify();       

        if($this->current_id_node == 0)
        {
            $this->tplVar['showAddArticle'] = FALSE;        
        }
        else
        {
            $this->tplVar['showAddArticle'] = TRUE;
        }
        
        // template variables
        //
        // data of the current node
        $this->tplVar['node']   = array();
        // data of the child nodes
        $this->tplVar['nodes']  = array();
        // data of the branch nodes
        $this->tplVar['branch'] = array();  
        // data of the node articles
        $this->tplVar['articles'] = array();         
        // errors
        $this->tplVar['error']  = FALSE;    
    }
     /**
     * has the logged the rights to modify?
     * at least edit (40) rights are required
     *
     */      
    private function allowModify()
    {      
        if($this->viewVar['loggedUserRole'] <= 40 )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}

?>