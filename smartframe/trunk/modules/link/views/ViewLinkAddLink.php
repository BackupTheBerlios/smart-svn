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
 
class ViewLinkAddLink extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'addlink';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public  $templateFolder = 'modules/link/templates/';
        
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        // init template array to fill with node data
        $this->tplVar['status']      = 1;
        $this->tplVar['title']       = '';
        $this->tplVar['url']         = '';
        $this->tplVar['description'] = '';
        $this->tplVar['branch'] = array();  
        $this->tplVar['childs'] = array();
        // Init error array
        $this->tplVar['error']  = array();


        $this->tplVar['id_node'] = $_REQUEST['id_node'];

        // add link
        if( isset($_POST['addlink']) )
        {
            if(TRUE === $this->validate())
            {
                if($_REQUEST['id_node'] != $_REQUEST['link_id_node'])
                {
                    $id_node = $_REQUEST['link_id_node'];
                } 
                else
                {
                    $id_node = $_REQUEST['id_node'];
                }
                if(FALSE !== ($new_id_link = $this->addLink( $id_node )))
                {
                    @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=link&id_node='.$id_node);
                    exit;
                }
            }
            $this->resetFormData();
        }                

        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->tplVar['tree'],
                                   'fields'  => array('id_parent','status','id_node','title')));   


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
    private function addLink( $id_node )
    { 
        $this->model->action('link', 'addLink', 
                             array('id_node' => (int)$id_node,
                                   'fields'  => array('title'       => SmartCommonUtil::stripSlashes((string)$_POST['title']),
                                                      'url'         => SmartCommonUtil::stripSlashes((string)$_POST['url']),
                                                      'description' => SmartCommonUtil::stripSlashes((string)$_POST['description']),
                                                      'status'      => (int)$_POST['status'])));        
    }
    
    private function validate()
    {
        if(!isset($_REQUEST['id_node']))
        {
            $this->tplVar['error'][] = '"id_node" isnt defined';
        }

        if($_REQUEST['link_id_node'] == 0)
        {
            $this->tplVar['error'][] = '"Top" navigation node isnt allowed';
        }        
        // fetch the current id_node. If no node the script assums that
        // we are at the top level with id_parent 0
        if(!isset($_REQUEST['title'])) 
        {
            $this->tplVar['error'][] = 'Field "title" isnt defined';
        }
        elseif(!is_string($_REQUEST['title']))
        {
            $this->tplVar['error'][] = 'Field "title" isnt from type string';
        }   
        elseif(empty($_REQUEST['title']))
        {
            $this->tplVar['error'][] = 'Field "title" is empty';
        }          

        if(!isset($_REQUEST['url'])) 
        {
            $this->tplVar['error'][] = 'Field "url" isnt set';
        }
        elseif(!is_string($_REQUEST['url']))
        {
            $this->tplVar['error'][] = 'Field "url" isnt from type string';
        }   
        elseif(empty($_REQUEST['url']))
        {
            $this->tplVar['error'][] = 'Field "url" is empty';
        }         

        if(count($this->tplVar['error']) > 0)
        {
            return FALSE;
        }
        return TRUE;
    }
    /**
     * reset the form fields with old link data
     *
     * @access privat
     */       
    private function resetFormData()
    {
        $this->tplVar['status']      = SmartCommonUtil::stripSlashes($_POST['status']);
        $this->tplVar['title']       = htmlspecialchars ( SmartCommonUtil::stripSlashes($_POST['title']), ENT_COMPAT, $this->config['charset'] );
        $this->tplVar['url']         = htmlspecialchars ( SmartCommonUtil::stripSlashes($_POST['url']), ENT_COMPAT, $this->config['charset'] );
        $this->tplVar['description'] = SmartCommonUtil::stripSlashes($_POST['description']);         
    }      
}

?>