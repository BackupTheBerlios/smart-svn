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
 * ViewNavigationMain
 *
 */
 
class ViewNavigationMain extends SmartView
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
        // init variables for this view
        $this->initVars();

        // move up or down a node
        if( isset($_GET['id_node_up']) && 
            !preg_match("/[^0-9]+/",$_GET['id_node_up']) &&
            ($this->allowModify() == TRUE) )
        {
            $this->model->action('navigation','moveNodeRank', 
                                 array('id_node' => (int)$_GET['id_node_up'],
                                       'dir'     => 'up'));        
        }
        elseif( isset($_GET['id_node_down']) && 
                !preg_match("/[^0-9]+/",$_GET['id_node_down']) &&
                ($this->allowModify() == TRUE) )
        {
            $this->model->action('navigation','moveNodeRank', 
                                 array('id_node' => (int)$_GET['id_node_down'],
                                       'dir'     => 'down'));        
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

        // get node locks
        $this->getLocks();
    }  
    
     /**
     * assign template variables with lock status of each node
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
                                           'id_node'    => (int)$node['id_node'],
                                           'by_id_user' => (int)$this->viewVar['loggedUserId']) );
                                           
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
        $this->tplVar['showLink'] = $this->allowModify();       
        
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