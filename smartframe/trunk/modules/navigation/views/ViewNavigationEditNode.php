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

        // is node locked by an other user
        if( TRUE == $this->isNodeLocked() )
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            return;        
        }

        if( isset($_POST['modifynodedata']) )
        {
            $this->updateNodeData();
            
            if(isset($_POST['finishupdate']))
            {
                @header('Location: '.SMART_CONTROLLER.'?mod=navigation&id_node='.$_POST['id_parent']);
                exit;
            }
        }

        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_parent' => 0,
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
}

?>