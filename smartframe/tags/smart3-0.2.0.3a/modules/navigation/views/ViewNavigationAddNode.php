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
 
class ViewNavigationAddNode extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'addnode';
    
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
        $this->tplVar['title']  = '';
        $this->tplVar['branch'] = array();  
        $this->tplVar['childs'] = array();
        // Init template form field values
        $this->tplVar['error']            = FALSE;

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
        
        // add node
        if( isset($_POST['addnode']) )
        {
            if(FALSE !== ($new_id_node = $this->addNode( $id_node )))
            {
                @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=navigation&view=editNode&id_node='.$new_id_node);
                exit;
                //throw new SmartForwardAdminViewException('naviagtion','index');
            }
        }
        
        // assign the template array $B->tpl_nodes with navigation nodes
        $this->model->action('navigation', 'getChilds', 
                             array('id_node' => (int)$id_node,
                                   'order'   => array('rank', 'asc'),
                                   'status'  => array('>=', 0),
                                   'fields'  => array('id_node','title','status'),
                                   'result'  => & $this->tplVar['childs'],
                                   'error'   => & $this->tplVar['error']));
                 
        // assign the template array $B->tpl_nodes with navigation nodes
        $this->model->action('navigation',
                             'getBranch', 
                             array('result'  => & $this->tplVar['branch'],
                                   'id_node' => (int)$id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node')));                 

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
    * add new node
    *
    * @param int $id_parent parent node of the new node
    */    
    private function addNode( $id_parent )
    {
        if(!isset($_POST['title']) || empty($_POST['title']))
        {
            $this->tplVar['error'] = 'Title is empty';
            return FALSE;
        }
        
        // init id_view
        $id_view = 0;
        // get associated view of the parent node
        if($id_parent != 0)
        {
            $tmp = array();
            // get current node data
            $this->model->action('navigation','getNode', 
                                 array('result'  => & $tmp,
                                       'id_node' => (int)$id_parent,
                                       'fields'  => array('id_view'))); 
            $id_view = $tmp['id_view'];
        }
        
        return $this->model->action('navigation', 'addNode', 
                             array('id_parent' => (int)$id_parent,
                                   'fields'    => array('title'   => SmartCommonUtil::stripSlashes((string)$_POST['title']),
                                                        'id_view' => (int)$id_view,
                                                        'status'  => 1)));        
    }
}

?>