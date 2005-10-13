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
 * ViewUserLogout class
 *
 */

class ViewUserAdminLogout extends SmartView
{    
    /**
     * Destroy current session and reload the admin controller
     *
     */
    public function perform()
    {
        // free locks from this user
        $this->model->broadcast('lock',array('job'     => 'unlock_from_user',
                                             'id_user' => (int)$this->viewVar['loggedUserId']));
        
        $this->model->session->destroy();
        ob_clean();
        @header('Location: ' . $this->config['admin_web_controller']);
        exit;        
    }  
}

?>