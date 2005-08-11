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
 * ViewNavigationMain
 *
 */
 
class ViewNavigationEditNode extends SmartView
{
   /**
     * template for this view
     * @var string $template
     */
    public $template = 'editnode';
    
   /**
     * template folder for this view
     * @var string $template_folder
     */    
    public $templateFolder = 'modules/navigation/templates/';
    
   /**
     * current id_node
     * @var int $current_id_node
     */
    private $current_id_node;    
   /**
     * execute the perform methode
     * @var bool $dontPerform
     */
    private $dontPerform;       
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
            $this->tplVar['error'] = 'You have not the rights to edit a node!';
            $this->dontPerform = TRUE;
        }

        // init variables for this view
        $this->initVars();

        // is node locked by an other user
        if( TRUE !== $this->lockNode() )
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->tplVar['error'] = 'This node is locked by an other user!';
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

        // change nothing and switch back
        if(isset($_POST['canceledit']) && ($_POST['canceledit'] == '1'))
        {
            $this->unlocknode();
            $this->redirect((int)$_POST['id_parent']);        
        }
        
        if( isset($_POST['modifynodedata']) )
        {      
            $this->updateNodeData();
        }

        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'    => & $this->tplVar['tree'],
                                   'fields'    => array('id_parent','status','id_node','title')));   
        
        // get current node data
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->tplVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','body','short_text',
                                                      'id_parent','media_folder','id_view',
                                                      'status','logo','id_node','format')));

        // convert some field values to safely include it in template html form fields
        $this->convertHtmlSpecialChars( $this->tplVar['node'], array('title') );        
    
        // get navigation node branch of the current node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->tplVar['branch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node')));   
                                   
        // get user picture thumbnails
        $this->model->action('navigation','getAllThumbs',
                             array('result'  => & $this->tplVar['thumb'],
                                   'id_node' => (int)$_REQUEST['id_node'],
                                   'order'   => 'rank',
                                   'fields'  => array('id_pic',
                                                      'file',
                                                      'size',
                                                      'mime',
                                                      'width',
                                                      'height',
                                                      'title',
                                                      'description')) );

        // convert description field to safely include into javascript function call
        $x=0;
        $this->tplVar['node']['thumbdesc'] = array();
        foreach($this->tplVar['thumb'] as $thumb)
        {
            $this->convertHtmlSpecialChars( $this->tplVar['thumb'][$x], array('description') );
            $this->tplVar['thumb'][$x]['description'] = addslashes($this->tplVar['thumb'][$x]['description']);
            $x++;
        }

        // get user files
        $this->model->action('navigation','getAllFiles',
                             array('result'  => & $this->tplVar['file'],
                                   'id_node' => (int)$_REQUEST['id_node'],
                                   'order'   => 'rank',
                                   'fields'  => array('id_file',
                                                      'file',
                                                      'size',
                                                      'mime',
                                                      'title',
                                                      'description')) );

        // convert files description field to safely include into javascript function call
        $x=0;
        $this->tplVar['node']['filedesc'] = array();
        foreach($this->tplVar['file'] as $file)
        {
            $this->convertHtmlSpecialChars( $this->tplVar['file'][$x], array('description') );
            $this->tplVar['file'][$x]['description'] = addslashes($this->tplVar['file'][$x]['description']);
            $x++;
        }    

        if( $this->tplVar['node']['id_view'] == 0 )
        {
            // get associated view of the parent node
            if($this->tplVar['node']['id_parent'] != 0)
            {
                // get current node data
                $this->model->action('navigation','getNode', 
                                     array('result'  => & $this->tplVar['node'],
                                           'id_node' => (int)$this->tplVar['node']['id_parent'],
                                           'error'   => & $this->tplVar['error'],
                                           'fields'  => array('id_view'))); 
            }
        }
        
        // get all available public views
        $this->tplVar['publicViews'] = array();
        $this->model->action( 'navigation','getNodePublicViews',
                              array('result' => &$this->tplVar['publicViews'],
                                    'fields' => array('id_view','name')) );     
    }  

    private function updateNodeData()
    {
        $node_was_moved  = FALSE;
        $use_text_format = FALSE;

        if(empty($_POST['title']))
        {
            $this->tplVar['error'] = 'Node title is empty!';
            return;
        }
        
        // check if id_parent has change
        if($_POST['id_parent'] != $_POST['node_id_parent'])
        {
            $id_parent = (string)$_POST['node_id_parent'];
            // check if the new id_parent isnt a subnode of the current node
            if(FALSE == $this->isSubNode( $id_parent, $_POST['id_node'] ))
            {
                $rank = $this->getLastRank( $id_parent );
                if($rank !== FALSE)
                {
                    $rank++;
                }
                else
                {
                    $rank = 0;
                }
                $node_was_moved = TRUE;
            }
            else
            {
                $this->tplVar['error'] = "Circular error! A new parent node cannot be a subnode of the current node.";
            }
        }
        else
        {
            $id_parent = (int)$_POST['id_parent'];
            $rank = FALSE;
        }
            
        if($_POST['delete_node'] == '1')
        {
            $this->deleteNode( $_POST['id_node'] );
            $this->reorderRank( (int)$_POST['id_parent'] );
            $this->redirect( $id_parent );
        }           
        // switch format of textarea editor
        elseif(isset($_POST['switchformat']) && $_POST['switchformat'] == 1)
        {
            $use_text_format = (int)$_POST['format'];
        }        
        // upload logo
        elseif(isset($_POST['uploadlogo']) && !empty($_POST['uploadlogo']))
        {   
            $this->model->action('navigation','uploadLogo',
                                 array('id_node'  => (int)$_REQUEST['id_node'],
                                       'postName' => 'logo') );                            
        }
        // delete logo
        elseif(isset($_POST['deletelogo']) && !empty($_POST['deletelogo']))
        {   
            $this->model->action('navigation','deleteLogo',
                                 array('id_node' => (int)$_REQUEST['id_node']) ); 
        }   
        // add picture
        elseif(isset($_POST['uploadpicture']) && !empty($_POST['uploadpicture']))
        {   
            $this->model->action('navigation','addItem',
                                 array('item'     => 'picture',
                                       'id_node'  => (int)$_REQUEST['id_node'],
                                       'postName' => 'picture',
                                       'error'    => & $this->tplVar['error']) ); 
        }
        // delete picture
        elseif(isset($_POST['imageID2del']) && !empty($_POST['imageID2del']))
        {
            $this->model->action('navigation','deleteItem',
                                 array('id_node' => (int)$_REQUEST['id_node'],
                                       'id_pic'  => (int)$_POST['imageID2del']) ); 
        }
        // move image rank up
        elseif(isset($_POST['imageIDmoveUp']) && !empty($_POST['imageIDmoveUp']))
        {   
            $this->model->action('navigation','moveItemRank',
                                 array('id_node' => (int)$_REQUEST['id_node'],
                                       'id_pic'  => (int)$_POST['imageIDmoveUp'],
                                       'dir'     => 'up') ); 
        }  
        // move image rank down
        elseif(isset($_POST['imageIDmoveDown']) && !empty($_POST['imageIDmoveDown']))
        {   
            $this->model->action('navigation','moveItemRank',
                                 array('id_node' => (int)$_REQUEST['id_node'],
                                       'id_pic'  => (int)$_POST['imageIDmoveDown'],
                                       'dir'     => 'down') ); 
        } 
        // move file rank up
        elseif(isset($_POST['fileIDmoveUp']) && !empty($_POST['fileIDmoveUp']))
        {
            $this->model->action('navigation','moveItemRank',
                                 array('id_node' => (int)$_REQUEST['id_node'],
                                       'id_file' => (int)$_POST['fileIDmoveUp'],
                                       'dir'     => 'up') );                                                 
        }
        // move file rank down
        elseif(isset($_POST['fileIDmoveDown']) && !empty($_POST['fileIDmoveDown']))
        {   
            $this->model->action('navigation','moveItemRank',
                                 array('id_node' => (int)$_REQUEST['id_node'],
                                       'id_file' => (int)$_POST['fileIDmoveDown'],
                                       'dir'     => 'down') );                                                
        } 
        // add file
        elseif(isset($_POST['uploadfile']) && !empty($_POST['uploadfile']))
        {          
            $this->model->action('navigation','addItem',
                                 array('item'     => 'file',
                                       'id_node'  => (int)$_REQUEST['id_node'],
                                       'postName' => 'ufile',
                                       'error'    => & $this->tplVar['error']) );                          
        }
        // delete file
        elseif(isset($_POST['fileID2del']) && !empty($_POST['fileID2del']))
        {   
            $this->model->action('navigation','deleteItem',
                                 array('id_node' => (int)$_REQUEST['id_node'],
                                       'id_file' => (int)$_POST['fileID2del']) ); 
        }  
        
        // update picture data if there images
        if(isset($_POST['picid']))
        {
            $this->model->action( 'navigation','updateItem',
                                  array('item'    => 'pic',
                                        'ids'     => &$_POST['picid'],
                                        'fields'  => array('description' => &$_POST['picdesc'],
                                                           'title'       => &$_POST['pictitle'])));
        }        

        // update file data if there file attachments
        if(isset($_POST['fileid']))
        {
            $this->model->action( 'navigation','updateItem',
                                  array('item'    => 'file',
                                        'ids'     => &$_POST['fileid'],
                                        'fields'  => array('description' => &$_POST['filedesc'],
                                                           'title'       => &$_POST['filetitle'])));
        }  
        
        // if no error occure update node data
        if($this->tplVar['error'] == FALSE)
        {
            // update node data
            $this->updateNode( $rank, $use_text_format );
            if($node_was_moved == TRUE)
            {
                $this->reorderRank( (int)$_POST['id_parent'] );
            }
            if( isset($_POST['finishupdate']) )
            {
                $this->unlockNode();
                $this->redirect( $id_parent );
            }
        }    
    }
     /**
     * is node locked by an other user?
     *
     */   
    private function lockNode()
    {
        return $this->model->action('navigation','lock',
                                    array('job'        => 'lock',
                                          'id_node'    => (int)$this->current_id_node,
                                          'by_id_user' => (int)$this->viewVar['loggedUserId']) );  
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

        // set format template var, means how to format textarea content -> editor/wikki ?
        // 1 = text_wikki
        // 2 = tiny_mce
        if($this->config['navigation']['force_format'] != 0)
        {
            $this->tplVar['format'] = $this->config['navigation']['force_format'];
            $this->tplVar['show_format_switch'] = FALSE;
        }
        elseif(isset($_POST['format']))
        {
            if(!preg_match("/(1|2){1}/",$_POST['format']))
            {
                $this->tplVar['format'] = $this->config['navigation']['default_format'];
            }
            $this->tplVar['format'] = $_POST['format'];
            $this->tplVar['show_format_switch'] = TRUE;
        }
        else
        {
            $this->tplVar['format'] = $this->config['navigation']['default_format'];
            $this->tplVar['show_format_switch'] = TRUE;
        }

        $this->tplVar['use_logo']      = $this->config['navigation']['use_logo'];
        $this->tplVar['use_images']    = $this->config['navigation']['use_images'];
        $this->tplVar['use_files']     = $this->config['navigation']['use_files'];
        $this->tplVar['use_shorttext'] = $this->config['navigation']['use_short_text'];        
        $this->tplVar['use_body']      = $this->config['navigation']['use_body'];
        $this->tplVar['lock_text']     = 'unlock';
        
        // template variables
        //
        // node tree data
        $this->tplVar['tree']   = array();
        // data of the current node
        $this->tplVar['node']   = array();
        // data of the branch nodes
        $this->tplVar['branch'] = array();  
        // data of thumbs an files attached to this node
        $this->tplVar['thumb']  = array();
        $this->tplVar['file']   = array();        
        // errors
        $this->tplVar['error']  = array();    
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
    /**
     * Convert strings so that they can be safely included in html forms
     *
     * @param array $var_array Associative array
     * @param array $fields Field names
     */
    private function convertHtmlSpecialChars( &$var_array, $fields )
    {
        foreach($fields as $f)
        {
            $var_array[$f] = htmlspecialchars ( $var_array[$f], ENT_COMPAT, $this->config['charset'] );
        }
    }  
    /**
     * Update node data
     *
     * @param int $rank New rank
     */
    private function updateNode( $rank, $format )
    {
        $fields = array('id_parent'  => (int)$_POST['node_id_parent'],
                        'status'     => (int)$_POST['status'],
                        'title'      => SmartCommonUtil::stripSlashes((string)$_POST['title']),
                        'short_text' => SmartCommonUtil::stripSlashes((string)$_POST['short_text']),
                        'body'       => SmartCommonUtil::stripSlashes((string)$_POST['body']));
                        
        if($rank != FALSE)
        {
            $fields['rank'] = $rank;
        }
        
        // only administrators can assign a node related view
        if($this->viewVar['loggedUserRole'] <= 20)
        {
            $fields['id_view'] = $_POST['id_view'];
        }
        
        if($format != FALSE)
        {
            $fields['format'] = $format;
        }        
        
        $this->model->action('navigation','updateNode',
                             array('id_node' => (int)$_REQUEST['id_node'],
                                   'fields'  => $fields));    
    }
    /**
     * Get last rank of an given id_parent
     *
     * @param int $id_parent
     */    
    private function deleteNode( $id_node )
    {
        $this->model->action('navigation','deleteNode',
                             array('id_node' => (int)$id_node));
    }    
    /**
     * check on subnode 
     * check if $id_node1 is a subnode of $id_node2
     *
     * @param int $id_node1
     * @param int $id_node2
     * @return bool True or False
     */    
    private function isSubNode( $id_node1, $id_node2  )
    {
        return $this->model->action('navigation','isSubNode',
                                    array('id_node1' => (int)$id_node1,
                                          'id_node2' => (int)$id_node2));
    }        
    /**
     * Get last rank of an given id_parent
     *
     * @param int $id_parent
     */    
    private function getLastRank( $id_parent )
    {
        $rank = 0;
        $this->model->action('navigation','getLastRank',
                             array('id_parent' => (int)$id_parent,
                                   'result'    => &$rank ));
        return $rank;
    }
    /**
     * reorder rank list when moving a node
     *
     * @param int $id_parent
     */      
    private function reorderRank( $id_parent )
    {
        $this->model->action('navigation','reorderRank',
                             array('id_parent' => (int)$id_parent));
    }  
    /**
     * Redirect to the main user location
     */
    private function redirect( $id_node = 0 )
    {
        // reload the user module
        @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=navigation&id_node='.$id_node);
        exit;      
    }  
    /**
     * unlock edited user
     *
     */     
    private function unlockNode()
    {
        $this->model->action('navigation','lock',
                             array('job'     => 'unlock',
                                   'id_node' => (int)$this->current_id_node));    
    }    
    
}

?>