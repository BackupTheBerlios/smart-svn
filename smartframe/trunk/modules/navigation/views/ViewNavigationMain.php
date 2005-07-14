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
 
class ViewNavigationMain extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'main';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public  $templateFolder = 'modules/navigation/templates/';
        
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        // init template variables
        
        // fetch the current id_node. If no node the script assums that
        // we are at the top level with id_parent 0
        if(!isset($_REQUEST['id_node'])) 
        {
            $this->tplVar['id_node']  = 0;
            $id_node = 0;
        }
        else
        {
            $this->tplVar['id_node']  = $_REQUEST['id_node'];
            $id_node = (int)$_REQUEST['id_node'];
        }

        // template variables
        //
        // data of the current node
        $this->tplVar['node']   = array();
        // data of the child nodes
        $this->tplVar['nodes']  = array();
        // data of the branch nodes
        $this->tplVar['branch'] = array();  
        // errors
        $this->tplVar['error']  = FALSE;
        
        // move up or down a node
        if( isset($_GET['id_node_up']) )
        {
            $this->model->action('navigation', 
                                 'moveNodeRank', 
                                 array('id_node' => (int)$_GET['id_node_up'],
                                       'dir'     => 'up'));        
        }
        elseif( isset($_GET['id_node_down']) )
        {
            $this->model->action('navigation', 
                                 'moveNodeRank', 
                                 array('id_node' => (int)$_GET['id_node_down'],
                                       'dir'     => 'down'));        
        }
        
        if($id_node != 0)
        {
            // assign the template array $B->tpl_nodes with navigation nodes
            $this->model->action('navigation', 
                                 'getNode', 
                                 array('result'  => & $this->tplVar['node'],
                                       'id_node' => $id_node,
                                       'error'   => & $this->tplVar['error'],
                                       'fields'  => array('title','id_node')));        
        }
    
        // assign the template array $B->tpl_nodes with navigation nodes
        $this->model->action('navigation', 
                             'getChilds', 
                             array('result'  => & $this->tplVar['nodes'],
                                   'id_node' => $id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node','id_parent','status')));
                 
        // assign the template array $B->tpl_nodes with navigation nodes
        $this->model->action('navigation',
                             'getBranch', 
                             array('result'  => & $this->tplVar['branch'],
                                   'id_node' => $id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node')));                 

        // get user locks
        $this->getLocks();

        // set template variable that show the link to add users
        // only if the logged user have at least editor rights
        if($this->viewVar['loggedUserRole'] <= 40)
        {
            $this->tplVar['showAddNodeLink'] = TRUE;
        }
        else
        {
            $this->tplVar['showAddNodeLink'] = FALSE;
        }
    }  
    
     /**
     * assign template variables with lock status of each user
     *
     */   
    private function getLocks()
    {
        $row = 0;
        
        foreach($this->tplVar['nodes'] as $node)
        {
            // lock the user to edit
            $result = $this->model->action('navigation','lock',
                                     array('job'        => 'is_locked',
                                           'id_node'    => $node['id_node'],
                                           'by_id_user' => $this->viewVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->tplVar['nodes'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->tplVar['nodes'][$row]['lock'] = FALSE;  
            }
            
            $row++;
        }    
    }    
}

?>