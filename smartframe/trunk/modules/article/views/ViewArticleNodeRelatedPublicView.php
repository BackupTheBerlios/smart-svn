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
 * ViewArticleNodeRelatedPublicView class
 *
 */

class ViewArticleNodeRelatedPublicView extends SmartView
{
     /**
     * Template for this view
     * @var string $template
     */
    public $template = 'nodeRelatedPublicView';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/article/templates/';
    
    /**
     * Execute the view
     *
     */
    public function perform()
    {
        if( isset($_POST['modifynodedata']) )
        {
            if($_POST['article_id_view'] != 0)
            {
                $this->updateArticleNodeView( (int)$_REQUEST['id_node'], (int)$_POST['article_id_view'] );
                // update subnodes
                if(isset($_POST['articleviewssubnodes']) && ($_POST['articleviewssubnodes'] == 1))
                {
                    $node_tree = array();
                    $this->model->action('navigation','getTree',
                                         array('id_node' => (int)$_REQUEST['id_node'],
                                               'result'  => &$node_tree, 
                                               'status'  => array('>',0),
                                               'fields'  => array('id_node','id_parent','status')));
                    foreach($node_tree as $node)
                    {
                        $this->updateArticleNodeView( (int)$node['id_node'], (int)$_POST['article_id_view'] );
                    }
                }
            }
        }        

        // get article associated public view
        $this->tplVar['articleAssociatedPublicView'] = array();
        $this->model->action( 'article','getNodeAssociatedView',
                              array('result'  => &$this->tplVar['articleAssociatedPublicView'],
                                    'id_node' => (int)$_REQUEST['id_node']) );     
        
        // get all available registered article public views
        $this->tplVar['articlePublicViews'] = array();
        $this->model->action( 'article','getPublicViews',
                              array('result' => &$this->tplVar['articlePublicViews'],
                                    'fields' => array('id_view','name')) );         
    }     
    
    private function updateArticleNodeView( $id_node, $id_view )
    {
        $this->model->action( 'article','updateNodeView',
                              array('id_node' => (int)$id_node,
                                    'id_view' => (int)$id_view) );     
    }
    
    
}

?>