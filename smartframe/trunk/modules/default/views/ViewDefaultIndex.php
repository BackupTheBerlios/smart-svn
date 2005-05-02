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
 * ViewCommonIndex class
 *
 */

class ViewDefaultIndex extends SmartView
{
     /**
     * Default template for this view
     * @var string $template
     */
    public $template = 'index';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/default/templates/';
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        // run test action of the common module
        // it assign a template variable with content
        $this->model->action( SMART_MOD_COMMON, 
                              'test', 
                              array( 'result'  => & $this->tplVar,
                                     'message' => 'This message comes from the default module' ) );
    }     
}

?>