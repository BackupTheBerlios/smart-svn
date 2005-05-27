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
 * Init action of the user module 
 *
 *
 */

class ActionUserInit extends SmartAction
{
    /**
     * User Module Version
     */
    const MOD_VERSION = '0.1';    
    
    /**
     * Run init process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        $this->checkModuleVersion();
        
        // Update the access time of the logged user
        $this->model->action('user','access',
                             array('job'     => 'update',
                                   'id_user' => $this->model->session->get('loggedUserId')));
    } 
    /**
     * Check module version and upgrade or install this module if necessairy
     *
     */    
    private function checkModuleVersion()
    {
        // get user module info
        $info = $this->model->getModuleInfo('user');
        
        // is module registered (installed) ?
        if(FALSE == $this->model->isModule( 'user' ))
        {
            // Install this module
            $this->model->action('user','setup',
                                  array('config' => & $this->config));            
        }
        // need upgrade?
        elseif(0 != version_compare($info['version'], self::MOD_VERSION))
        {
            // Upgrade this module
            $this->model->action('user','upgrade',array('new_version' => self::MOD_VERSION));           
        }
        
        unset($info);
    }
}

?>