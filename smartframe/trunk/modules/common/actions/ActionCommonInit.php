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
 * Init action of the common module 
 *
 */
 
class ActionCommonInit extends SmartAction
{
    /**
     * Run init process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        
        // Check if a setup was successfull done else launch setup > 'setup' module

        if(file_exists(SMART_CONFIG_PATH . 'config.php'))
        {
            include_once(SMART_CONFIG_PATH . 'config.php');
        }
        else
        {
            throw new SmartForwardViewException( SMART_MOD_SETUP );        
        }
        
        // module configuration array
        // usually fetched from a database table
        $config = array('publicViewFolder'     => 'views_default',
                        'publicTemplateFolder' => 'templates_default');
                        
        // Set module configuration variables
        $this->model->addConfigVar( 'common', $config );    
    } 
}

?>