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
 
class ViewKeywordAddKeyword extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'addkeyword';
    
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
        // init template array to fill with node data
        $this->tplVar['title']  = '';
        $this->tplVar['branch'] = array();  
        $this->tplVar['childs'] = array();
        // Init template form field values
        $this->tplVar['error']            = FALSE;

        // fetch the current id_key. If no node the script assums that
        // we are at the top level with id_parent 0
        if(!isset($_REQUEST['id_key'])) 
        {
            $this->tplVar['id_key']  = 0;
            $id_key = 0;
        }
        else
        {
            $this->tplVar['id_key']  = $_REQUEST['id_key'];
            $id_key = (int)$_REQUEST['id_key'];
        }
        
        // add node
        if( isset($_POST['addkeyword']) )
        {
            if(FALSE !== ($new_id_key = $this->addKeyword( $id_key )))
            {
                @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=keyword&view=editKeyword&id_key='.$new_id_key);
                exit;
            }
        }
        
        // assign the template array $B->tpl_nodes with navigation nodes
        $this->model->action('keyword', 'getChilds', 
                             array('id_key'  => (int)$id_key,
                                   'status'  => array('>=', 0),
                                   'fields'  => array('id_key','title','status'),
                                   'result'  => & $this->tplVar['childs'],
                                   'error'   => & $this->tplVar['error']));
                 
        // assign the template array $B->tpl_nodes with navigation nodes
        $this->model->action('keyword','getBranch', 
                             array('result'  => & $this->tplVar['branch'],
                                   'id_key'  => (int)$id_key,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_key')));                 

        // set template variable that show the link to add users
        // only if the logged user have at least editor rights
        if($this->viewVar['loggedUserRole'] <= 40)
        {
            $this->tplVar['showAddKeywordLink'] = TRUE;
        }
        else
        {
            $this->tplVar['showAddKeywordLink'] = FALSE;
        }
    }   
   /**
    * add new node
    *
    * @param int $id_parent parent node of the new node
    */    
    private function addKeyword( $id_parent )
    {
        if(!isset($_POST['title']) || empty($_POST['title']))
        {
            $this->tplVar['error'] = 'Title is empty';
            return FALSE;
        }
        
        return $this->model->action('keyword', 'add', 
                             array('fields' => array('title'     => SmartCommonUtil::stripSlashes((string)$_POST['title']),
                                                     'id_parent' => (int)$id_parent,
                                                     'status'    => 1)));        
    }
}

?>