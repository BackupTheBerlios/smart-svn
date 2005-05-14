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
    function perform()
    {
        // init users template variable 
        $this->tplVar['users'] = array();
        
        // assign template variable with users
        $this->model->action('user', 
                             'getUsers',
                             array('result'         => & $this->tplVar['users'],
                                   'translate_role' => TRUE,
                                   'fields' => array('id_user',
                                                     'status',
                                                     'login',
                                                     'role',
                                                     'name',
                                                     'lastname')));                                                 
    }     
}

?>