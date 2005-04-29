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
        // Check if a setup was successful done else launch setup > 'setup' module
        
        
        // module configuration array
        // usually fetched from a database table
        $config = array('publicViewFolder'     => 'views_default',
                        'publicTemplateFolder' => 'templates_default');
                        
        // Set module configuration variables
        $this->model->addConfigVar( 'common', $config );    
    } 
}

?>