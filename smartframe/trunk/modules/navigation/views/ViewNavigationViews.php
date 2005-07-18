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
 * ViewNavigationViews
 *
 */
 
class ViewNavigationViews extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'views';
    
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
        if(isset($_REQUEST['register']))
        {
            if(is_array($_POST['availableview']))
            {
                foreach($_POST['availableview'] as $name)
                {
                    $this->model->action('navigation','registerViews',
                                         array('action' => 'register',
                                               'name'   => strtolower((string)$name)) );  
                }
            }
        }
        elseif(isset($_REQUEST['unregister']))
        {
            if(is_array($_POST['registeredview']))
            {
                foreach($_POST['registeredview'] as $id_view)
                {
                    $this->model->action('navigation','registerViews',
                                         array('action'  => 'unregister',
                                               'id_view' => (int)$id_view) );  
                }
            }
        }        
        
        // get all available public views
        $this->tplVar['availableViews'] = array();
        $this->model->action( 'common','getAllPublicViews',
                              array('result' => &$this->tplVar['availableViews']) );   
                                    
        // get all available public views
        $this->tplVar['registeredViews'] = array();
        $this->model->action( 'navigation','getNodePublicViews',
                              array('result' => &$this->tplVar['registeredViews'],
                                    'fields' => array('id_view','name')) );
        
        return TRUE;
    }   
    
    private function isRegisteredPublicView()
    {
        $this->current_reg_nodes = array();
        $this->model->action( 'navigation','getNodePublicViews',
                              array('result' => &$this->current_reg_nodes,
                                    'fields' => array('id_view','name')) );  
                                    
        foreach($_POST['view'] as $v_name)
        {
            foreach($this->current_reg_nodes as $_v)
            {
                if($v_name == $_v['name'])
                {
                    return $_v['id_view'];
                }
            }
        }
        return FALSE;
    }
}

?>