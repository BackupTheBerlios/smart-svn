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
 * Init action of the Navigation module 
 *
 * Modul-Dependencies:
 * - Navigation Modul
 * - Keyword Modul
 *
 *
 */

class ActionLinkInit extends SmartAction
{
    /**
     * Navigation Module Version
     */
    const MOD_VERSION = '0.1';    
    
    /**
     * Run init process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        $this->checkModuleVersion();
    } 
    /**
     * Check module version and upgrade or install this module if necessairy
     *
     */    
    private function checkModuleVersion()
    {
        // get user module info
        $info = $this->model->getModuleInfo('link');
        
        // need install or upgrade?
        if(0 != version_compare($info['version'], self::MOD_VERSION))
        {
            // Upgrade this module
            $this->model->action('link','upgrade',array('new_version' => self::MOD_VERSION));           
        }
        
        unset($info);
    }
}

?>