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
 * ViewKeywordMap
 *
 */
 
class ViewKeywordMap extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'map';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public  $templateFolder = 'modules/keyword/templates/';
    
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
            $this->tplVar['opener_url_vars'] = '&addkey=1&' . base64_decode((string)$_REQUEST['opener_url_vars']);
        }
        else
        {
            $this->tplVar['mod'] = 'keyword';
            $this->tplVar['opener_url_vars'] = '';
        }
        
        $this->tplVar['tree'] = array();
        
        // get whole node tree
        $this->model->action('keyword','getTree', 
                             array('id_key' => 0,
                                   'result'  => & $this->tplVar['tree'],
                                   'status'  => array('>', 0),
                                   'fields'  => array('id_parent','status','id_key','title')));   
    }   
}

?>
