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
 * ViewNavigationNodemap
 *
 */
 
class ViewNavigationNodemap extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'nodemap';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public  $templateFolder = 'modules/navigation/templates/';

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // if no rights for the logged user, show error template
        // only administrators can change options
        if($this->viewVar['loggedUserRole'] > 20)
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->tplVar['error'] = 'You have not the rights to change navigation module options!';
            $this->dontPerform = TRUE;
        }
    } 
    
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {   
        // get the opener module
        if(isset($_REQUEST['openerModule']))
        {
            $this->tplVar['mod'] = (string)$_REQUEST['openerModule'];
            $this->tplVar['url_pager_var'] = (string)$_REQUEST['openerModule'].'_page=1';
        }
        else
        {
            $this->tplVar['url_pager_var'] = '';
            $this->tplVar['mod'] = 'navigation';
        }
        
        $this->tplVar['tree'] = array();
        
        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->tplVar['tree'],
                                   'status'  => array('=', 2),
                                   'fields'  => array('id_parent','status','id_node','title')));   
    }   
}

?>
