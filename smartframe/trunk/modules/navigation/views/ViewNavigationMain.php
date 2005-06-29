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
        // init template array to fill with node data
        $this->tplVar['nodes']  = array();
        $this->tplVar['branch'] = array();        
        // Init template form field values
        $this->tplVar['error']            = FALSE;
        
        // move up or down a node
        if( isset($_GET['dir']) )
        {
            $this->model->action('navigation', 
                                 'moveNodeRank', 
                                 array('node'  => (int)$_GET['dir_node'],
                                       'dir'   => $_GET['dir'],
                                       'error' => & $this->tplVar['error']));        
        }
        
        if( !isset($_GET['node']) )
        {
            $node = 0;
        }
        else
        {
            $node = (int)$_GET['node'];
        }
        
        // assign the template array $B->tpl_nodes with navigation nodes
        $this->model->action('navigation', 
                             'getChilds', 
                             array('result' => & $this->tplVar['nodes'],
                                   'node'   => $node,
                                   'error'  => & $this->tplVar['error']));
                 
        // assign the template array $B->tpl_nodes with navigation nodes
        $this->model->action('navigation',
                             'getBranch', 
                             array('result'     => & $this->tplVar['branch'],
                                   'node_title' => 'tpl_node_title',
                                   'node'       => $node,
                                   'error'      => & $this->tplVar['error']));                 

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
}

?>