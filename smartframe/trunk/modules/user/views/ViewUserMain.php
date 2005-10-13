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
 * ViewUserMain class
 *
 */

class ViewUserMain extends SmartView
{
     /**
     * Template for this view
     * @var string $template
     */
    public $template = 'main';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/user/templates/';
    
    /**
     * Execute the view
     *
     */
    public function perform()
    {
        // init users template variable 
        $this->tplVar['users'] = array();
        
        // assign template variable with users
        $this->model->action('user', 
                             'getUsers',
                             array('result'         => & $this->tplVar['users'],
                                   'translate_role' => TRUE,
                                   'or_id_user'     => (int)$this->viewVar['loggedUserId'],
                                   'role'           => array('>',$this->viewVar['loggedUserRole']),
                                   'fields' => array('id_user','status',
                                                     'login','role',
                                                     'name','lastname')));  
        
        // get user locks
        $this->getLocks();
        
        // set template variable that show the link to add users
        // only if the logged user have at least editor rights
        if($this->viewVar['loggedUserRole'] <= 40)
        {
            $this->tplVar['showAddUserLink'] = TRUE;
        }
        else
        {
            $this->tplVar['showAddUserLink'] = FALSE;
        }
    }  
     /**
     * assign template variables with lock status of each user
     *
     */   
    private function getLocks()
    {
        $row = 0;
        
        foreach($this->tplVar['users'] as $user)
        {
            // lock the user to edit
            $result = $this->model->action('user','lock',
                                     array('job'        => 'is_locked',
                                           'id_user'    => (int)$user['id_user'],
                                           'by_id_user' => (int)$this->viewVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->tplVar['users'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->tplVar['users'][$row]['lock'] = FALSE;  
            }
            
            $row++;
        }    
    }
}

?>