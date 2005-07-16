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
    * Perform on the main view
    *
    */
    public function perform()
    {
        // if no rights for the logged user, show error template
        if( FALSE == $this->allowModify() )
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            return;
        }

        // init variables for this view
        $this->initVars();
        $node_was_moved = FALSE;

        // is node locked by an other user
        if( TRUE == $this->isNodeLocked() )
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            return;        
        }

        if( isset($_POST['modifynodedata']) )
        {           
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
                $id_parent = (string)$_POST['id_parent'];
                $rank = FALSE;
            }
            
            if($_POST['delete_node'] == '1')
            {
                $this->deleteNode( $_POST['id_node'] );
                $this->reorderRank( (int)$_POST['id_parent'] );
                @header('Location: '.SMART_CONTROLLER.'?mod=navigation&id_node='.$id_parent);
                exit;
            }           
            
            // if no error occure update node data
            if($this->tplVar['error'] == FALSE)
            {
                // update node data
                $this->updateNodeData( $rank );
                if($node_was_moved == TRUE)
                {
                    $this->reorderRank( (int)$_POST['id_parent'] );
                }
                if( isset($_POST['finishupdate']) )
                {
                    @header('Location: '.SMART_CONTROLLER.'?mod=navigation&id_node='.$id_parent);
                    exit;
                }
            }
        }

        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'    => & $this->tplVar['tree'],
                                   'fields'    => array('id_parent','status','id_node','title')));   
        
        // get current node data
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->tplVar['node'],
                                   'id_node' => $this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','body','short_text',
                                                      'id_parent','media_folder',
                                                      'status','logo','id_node')));

        // convert some field values to safely include it in template html form fields
        $this->convertHtmlSpecialChars( $this->tplVar['node'], array('title') );        
    
        // get navigation node branch of the current node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->tplVar['branch'],
                                   'id_node' => $this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node')));                 
    }  
    
     /**
     * is node locked by an other user?
     *
     */   
    private function isNodeLocked()
    {
        $result = $this->model->action('navigation','lock',
                                       array('job'        => 'is_locked',
                                             'id_node'    => $this->current_id_node,
                                             'by_id_user' => $this->viewVar['loggedUserId']) );
                                           
        if(($result !== TRUE) && ($result !== FALSE))
        {
            return TRUE;  
        } 
        else
        {
            return FALSE;  
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
        
        // template variables
        //
        // node tree data
        $this->tplVar['tree']   = array();
        // data of the current node
        $this->tplVar['node']   = array();
        // data of the branch nodes
        $this->tplVar['branch'] = array();  
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
    private function updateNodeData( $rank )
    {
        $fields = array('id_parent'  => $_POST['node_id_parent'],
                        'status'     => $_POST['status'],
                        'title'      => SmartCommonUtil::stripSlashes($_POST['title']),
                        'short_text' => SmartCommonUtil::stripSlashes($_POST['short_text']),
                        'body'       => SmartCommonUtil::stripSlashes($_POST['body']));
                        
        if($rank != FALSE)
        {
            $fields['rank'] = $rank;
        }
        
        $this->model->action('navigation','updateNode',
                             array('id_node' => $_REQUEST['id_node'],
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
                             array('id_node' => $id_node));
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
                                    array('id_node1' => $id_node1,
                                          'id_node2' => $id_node2));
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
                             array('id_parent' => $id_parent,
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
                             array('id_parent' => $id_parent));
    }    
}

?>