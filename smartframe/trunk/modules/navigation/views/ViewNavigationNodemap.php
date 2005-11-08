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
