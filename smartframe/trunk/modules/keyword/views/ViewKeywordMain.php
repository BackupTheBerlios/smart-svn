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
 * ViewKeywordMain
 *
 */
 
class ViewKeywordMain extends SmartView
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
    public $templateFolder = 'modules/keyword/templates/';
    
   /**
     * current id_key
     * @var int $current_id_key
     */
    private $current_id_key;    
        
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        // init variables for this view
        $this->initVars();
        
        // get current node data if we arent at the top level node
        if($this->current_id_key != 0)
        {
            $this->model->action('keyword','getKeyword', 
                                 array('result' => & $this->tplVar['key'],
                                       'id_key' => (int)$this->current_id_key,
                                       'error'  => & $this->tplVar['error'],
                                       'fields' => array('title','id_key')));        
        }
    
        // get child navigation nodes
        $this->model->action('keyword','getChilds', 
                             array('result'  => & $this->tplVar['keys'],
                                   'id_key'  => (int)$this->current_id_key,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_key','id_parent','status')));
 
        // get navigation node branch of the current node
        $this->model->action('keyword','getBranch', 
                             array('result'  => & $this->tplVar['branch'],
                                   'id_key' => (int)$this->current_id_key,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_key')));                 

        // get keyword locks
        $this->getLocks();
    }  
    
     /**
     * assign template variables with lock status of each node
     *
     */   
    private function getLocks()
    {
        $row = 0;
        
        foreach($this->tplVar['keys'] as $node)
        {
            // lock the user to edit
            $result = $this->model->action('keyword','lock',
                                     array('job'        => 'is_locked',
                                           'id_key'     => (int)$node['id_key'],
                                           'by_id_user' => (int)$this->viewVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->tplVar['keys'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->tplVar['keys'][$row]['lock'] = FALSE;  
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
        // fetch the current id_key. If no node the script assums that
        // we are at the top level with id_parent 0
        if( !isset($_REQUEST['id_key']) || preg_match("/[^0-9]+/",$_REQUEST['id_key']) ) 
        {
            $this->tplVar['id_key']  = 0;
            $this->current_id_key    = 0;      
        }
        else
        {
            $this->tplVar['id_key']  = (int)$_REQUEST['id_key'];
            $this->current_id_key    = (int)$_REQUEST['id_key'];          
        }

        // set template variable to show edit links        
        $this->tplVar['showLink'] = $this->allowModify();       
        
        // template variables
        //
        // data of the current node
        $this->tplVar['key']   = array();
        // data of the child nodes
        $this->tplVar['keys']  = array();
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