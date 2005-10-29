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
 * ViewArticleEditArticle
 *
 * Article Status:
 * 0 = delete
 * 1 = cancel
 * 2 = propose
 * 3 = edit
 * 4 = publish
 * 5 = protect
 *
 */
 
class ViewArticleEditArticle extends SmartView
{
   /**
     * template for this view
     * @var string $template
     */
    public $template = 'editarticle';
    
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
     * current id_article
     * @var int $current_id_article
     */
    private $current_id_article;   
    
   /**
     * execute the perform methode
     * @var bool $dontPerform
     */
    private $dontPerform = FALSE;  
    
    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // if no rights for the logged user, show error template
        if( FALSE == $this->allowModify() )
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->tplVar['error'] = 'You have not the rights to edit a article!';
            $this->dontPerform = TRUE;
        }

        // init variables for this view
        if(FALSE == $this->initVars())
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->tplVar['error'] = 'Fatal error during variables init for this view';
            $this->dontPerform = TRUE;          
        }

        // is node locked by an other user
        if( TRUE !== $this->lockArticle() )
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->tplVar['error'] = 'This article is locked by an other user!';
            $this->dontPerform = TRUE;      
        }
    }        
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        if($this->dontPerform == TRUE)
        {
            return;
        }

        // forward to node x without update
        if(isset($_POST['gotonode']) && ($_POST['gotonode'] != ''))
        {
            $this->unlockArticle();
            $this->model->session->del('id_node');
            $this->model->session->del('id_article');
            $this->redirect((int)$_POST['gotonode']);        
        }

        // change nothing and switch back
        if(isset($_POST['canceledit']) && ($_POST['canceledit'] == '1'))
        {
            $this->unlockArticle();
            $this->model->session->del('id_node');
            $this->model->session->del('id_article');
            $this->redirect((int)$this->current_id_node);        
        }
        
        // update article data
        if( isset($_POST['modifyarticledata']) )
        {      
            $this->updateArticleData();
        }

        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->tplVar['tree'],
                                   'fields'  => array('id_parent','status',
                                                      'id_node','title')));   

        // article fields to get
        $articleFields = array('id_article','title','pubdate','status');
        // add fields depended on configuration settings
        $this->addGetArticleFields( $articleFields );

        // get demanded article data
        $this->model->action('article','getArticle', 
                             array('result'     => & $this->tplVar['article'],
                                   'id_article' => (int)$this->current_id_article,
                                   'error'      => & $this->tplVar['error'],
                                   'fields'     => $articleFields));

        // assign template date variables
        $this->assignTemplateDates();       

        // get current node data
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->tplVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node')));    
    
        // get navigation node branch of the current node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->tplVar['branch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node')));                             

        // we need the url vars to open this page by the keyword map window
        if($this->config['article']['use_keywords'] == 1)
        {
            if(isset($_REQUEST['addkey']))
            {
                $this->addKeyword();
            }
            $this->getArticleKeywords();
        }
    }  
   /**
    * Update article data
    *
    */
    private function updateArticleData()
    {   
        if(count($this->tplVar['error']) == 0)
        {
            // get the node ID of this article
            $this->getNewIdNode();

            $this->deleteArticleKeywords();
            $this->updateArticle();
            $this->unlockArticle();
            $this->model->session->del('id_node');
            $this->model->session->del('id_article');
            if(!isset($_POST['refresh']))
            {
                $this->redirect( $this->current_id_node );
            }
        }    
    }
     /**
     * lock this article
     *
     */   
    private function lockArticle()
    {
        return $this->model->action('article','lock',
                array('job'        => 'lock',
                      'id_article' => (int)$this->current_id_article,
                      'by_id_user' => (int)$this->viewVar['loggedUserId']) );  
    }   
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        // get node Id of the demanded article
        if(isset($_REQUEST['id_node']))
        {
            if(!isset($_REQUEST['id_node']) || 
               preg_match("/[^0-9]+/",$_REQUEST['id_node']) )
            {
                return FALSE;
            } 
            $this->current_id_node = $_REQUEST['id_node'];
            $this->model->session->set('id_node', (int)$_REQUEST['id_node']);        
        }
        elseif(NULL === ($this->current_id_node = $this->model->session->get('id_node')))
        {
            return FALSE;
        }  
        // get article ID
        if(isset($_REQUEST['id_article']))
        {
            if(!isset($_REQUEST['id_article']) || 
               preg_match("/[^0-9]+/",$_REQUEST['id_article']) )
            {
                return FALSE;
            } 
            $this->current_id_article = $_REQUEST['id_article'];
            $this->model->session->set('id_article', (int)$_REQUEST['id_article']);        
        }
        // get demanded article Id
        elseif(NULL === ($this->current_id_article = $this->model->session->get('id_article')))
        {
            return FALSE;
        }

        // template variables
        //
        // article data
        $this->tplVar['id_article'] = $this->current_id_article;
        $this->tplVar['id_node']    = $this->current_id_node;
        
        // node tree data
        $this->tplVar['tree']   = array();
        // data of the current node
        $this->tplVar['node']   = array();
        // data of the branch nodes
        $this->tplVar['branch'] = array();  
        // article data
        $this->tplVar['article']  = array();
       
        // errors
        $this->tplVar['error']  = array();   

        // assign template config vars
        foreach($this->config['article'] as $key => $val)
        {
            $this->tplVar[$key] = $val;
        }

        // we need the url vars to open this page by the keyword map window
        if($this->config['article']['use_keywords'] == 1)
        {
            $this->tplVar['opener_url_vars'] = base64_encode('&view=editArticle&id_article='.$this->current_id_article.'&id_node='.$this->current_id_node.'&disableMainMenu=1');
        }
        
        return TRUE;
    }
     /**
     * has the logged user the rights to modify article data?
     * at least 'edit' (40) rights are required
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
 
    /**
     * Update article data
     *
     */
    private function updateArticle()
    {
        $this->getNewIdNode();
        
        $articleFields = array('id_node'  => (int)$this->current_id_node,
                               'status'   => (int)$_POST['status'],
                               'pubdate'  => $this->buildDate('pubdate'));

        if(isset($this->node_has_changed))
        {
            $articleFields['rank'] = $this->getLastRank( $this->current_id_node );
        }

        // add fields depended on configuration settings
        $this->addSetArticleFields( $articleFields ); 
        
        $this->model->action('article','updateArticle',
                             array('id_article' => (int)$this->current_id_article,
                                   'error'      => &$this->tplVar['error'],
                                   'fields'     => $articleFields));    

        if(isset($this->node_has_changed))
        {
            $this->reorderRank( $_REQUEST['id_node'] );
        }
    }
    
    /**
     * build datetime
     *
     */    
    private function buildDate( $_date )
    {
        if(isset($_POST[$_date.'_year']))
        {
            return $_POST[$_date.'_year'].'-'.$_POST[$_date.'_month'].'-'.$_POST[$_date.'_day'].' '.$_POST[$_date.'_hour'].':'.$_POST[$_date.'_minute'].':00';
        }
        return '';
    }
        
    /**
     * Redirect to the article node
     */
    private function redirect( $id_node = 0 )
    {
        @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=article&id_node='.$id_node);
        exit;      
    }  
    
    /**
     * unlock article
     *
     */     
    private function unlockArticle()
    {
        $this->model->action('article','lock',
                             array('job'        => 'unlock',
                                   'id_article' => (int)$this->current_id_article));    
    }    
    
    /**
     * assignTemplateDates
     *
     */      
    private function assignTemplateDates()  
    {
        if( isset($this->tplVar['article']['pubdate']) )
        {
            if( preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})/",
                           $this->tplVar['article']['pubdate'], $d) )
            {
                $this->tplVar['article']['pubdate'] = array();
                $this->tplVar['article']['pubdate']['year']   = $d[1]; 
                $this->tplVar['article']['pubdate']['month']  = $d[2];
                $this->tplVar['article']['pubdate']['day']    = $d[3];
                $this->tplVar['article']['pubdate']['hour']   = $d[4];
                $this->tplVar['article']['pubdate']['minute'] = $d[5];
            }
        }
        if( isset($this->tplVar['article']['articledate']) )
        {
            if( preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})/",
                           $this->tplVar['article']['articledate'], $d2) )
            {
                $this->tplVar['article']['articledate'] = array();
                $this->tplVar['article']['articledate']['year']   = $d2[1]; 
                $this->tplVar['article']['articledate']['month']  = $d2[2];
                $this->tplVar['article']['articledate']['day']    = $d2[3];
                $this->tplVar['article']['articledate']['hour']   = $d2[4];
                $this->tplVar['article']['articledate']['minute'] = $d2[5];
            }
        }  
        if( isset($this->tplVar['article']['changedate']) )
        {
            if( preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})/",
                           $this->tplVar['article']['changedate'], $d3) )
            {
                $this->tplVar['article']['changedate'] = array();
                $this->tplVar['article']['changedate']['year']   = $d3[1]; 
                $this->tplVar['article']['changedate']['month']  = $d3[2];
                $this->tplVar['article']['changedate']['day']    = $d3[3];
                $this->tplVar['article']['changedate']['hour']   = $d3[4];
                $this->tplVar['article']['changedate']['minute'] = $d3[5];
                $this->tplVar['article']['changestatus']         = (int)$this->tplVar['article']['changestatus'];
                $this->tplVar['cd_disable'] = FALSE;
            }
        }
        elseif($this->config['article']['use_changedate'] == 1)
        {
                $this->tplVar['article']['changedate'] = array();
                $this->tplVar['article']['changedate']['year']   = date("Y", time()); 
                $this->tplVar['article']['changedate']['month']  = date("m", time());
                $this->tplVar['article']['changedate']['day']    = date("d", time());
                $this->tplVar['article']['changedate']['hour']   = date("H", time());
                $this->tplVar['article']['changedate']['minute'] = date("i", time());  
                $this->tplVar['article']['changestatus']         = 1;
                $this->tplVar['cd_disable'] = TRUE;
        }
    }

    /**
     * add article fields to get depended on the configuration settings
     *
     */     
    private function addGetArticleFields( & $articleFields )
    {
        if($this->config['article']['use_articledate'] == 1)
        {
            array_push($articleFields, 'articledate');
        }
        if($this->config['article']['use_changedate'] == 1)
        {
            array_push($articleFields, 'changedate');
        }        
    }

    /**
     * set article field values depended on the configuration settings
     *
     */      
    private function addSetArticleFields( & $articleFields )
    {
        if($this->config['article']['use_articledate'] == 1)
        {
            $articleFields['articledate'] = $this->buildDate('articledate');
        }
        if(($this->config['article']['use_changedate'] == 1))
        {
            if(!isset($_POST['changedate_year']))
            {
                $articleFields['changedate'] = FALSE;
            }
            else
            {
                $articleFields['changedate']   = $this->buildDate('changedate');
                $articleFields['changestatus'] = (int)$_POST['changestatus'];
            }
        }        
    } 
    
    /**
     * get article node ID
     *
     */      
    private function getNewIdNode()
    {
        if($_POST['article_id_node'] != $this->current_id_node)
        {
            if($_POST['article_id_node'] !== '0')
            {
                $this->current_id_node = (int)$_POST['article_id_node'];
                $this->node_has_changed = TRUE;
            }
        }
    } 
    
    /**
     * Get last rank of an given id_node
     *
     * @param int $id_node
     */    
    private function getLastRank( $id_node )
    {
        $rank = 0;
        $this->model->action('article','getLastRank',
                             array('id_node' => (int)$id_node,
                                   'result'  => &$rank ));

        if($rank > 0)
        {
            $rank++;
        }
        return $rank;
    }
    /**
     * reorder rank list when moving a node
     *
     * @param int $id_node
     */      
    private function reorderRank( $id_node )
    {
        $this->model->action('article','reorderRank',
                             array('id_node' => (int)$id_node));
    }    
    /**
     * reorder rank list when moving a node
     *
     * @param int $id_node
     */      
    private function addKeyword()
    {
        // get demanded article data
        $this->model->action('article','addKeyword', 
                             array('id_key'     => (int)$_REQUEST['id_key'],
                                   'id_article' => (int)$this->current_id_article));
    }  
    
    /**
     * reorder rank list when moving a node
     *
     * @param int $id_node
     */      
    private function getArticleKeywords()
    {
        $this->tplVar['keys'] = array();
        
        $keywords = array();
        
        // get demanded article data
        $this->model->action('article','getKeywordIds', 
                             array('result'     => & $keywords,
                                   'id_article' => (int)$this->current_id_article));

        foreach($keywords as $key)
        {
            $tmp = array();
            $tmp['id_key'] = $key; 
            
            $keyword = array();
            $this->model->action('keyword','getKeyword', 
                                 array('result' => & $keyword,
                                       'id_key' => (int)$key,
                                       'fields' => array('title','id_key')));          
            $branch = array();
            // get navigation node branch of the current node
            $this->model->action('keyword','getBranch', 
                                 array('result'  => & $branch,
                                       'id_key' => (int)$key,
                                       'fields'  => array('title','id_key')));                 

            $tmp['branch'] = '';
            
            foreach($branch as $bkey)
            {
                $tmp['branch'] .= '/'.$bkey['title'];
            }
            
            $tmp['branch'] .= '/<strong>'.$keyword['title'].'</strong>';
            
            $this->tplVar['keys'][] = $tmp;
        }
        sort($this->tplVar['keys']);    
    }   
    
    private function deleteArticleKeywords()
    {
        if(isset($_POST['id_key']) && is_array($_POST['id_key']))
        {
            foreach($_POST['id_key'] as $id_key)
            {
                // get navigation node branch of the current node
                $this->model->action('article','removeKeyword', 
                                 array('id_key'     => (int)$id_key,
                                       'id_article' => (int)$this->current_id_article));                 
            
            }
        }
    }
}

?>