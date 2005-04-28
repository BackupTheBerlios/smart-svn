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
 * ViewIndex class
 * Every view must extends SmartView
 *
 * The parent variables are:
 * $tplVar  - Array that may contains template variables
 * $viewVar - Array that may contains view variables, which
 *            are needed by some followed nested views.
 * $model   - The model object
 *            We need it to call modules actions
 * $renderTemplate - Is there a template associated with this view?
 *                   SMART_TEMPLATE_RENDER or SMART_TEMPLATE_RENDER_NONE
 * $viewData - Data passed to this view by the caller
 */

class ViewIndex extends SmartView
{
     /**
     * Template of this view
     * @var string $template
     */
    var $template = 'index';

    /**
     * Execute the view of the template "templates_xxx/tpl.index.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // run test action of the common module
        // it assign a template variable with content
        $this->model->CommonTest( array( 'result' => & $this->tplVar ) );

        return TRUE;
    }

    /**
     * authentication
     *
     */
    function auth()
    {
        // Here we call the authentication action of the user module
        // $this->model->UserAuth();
    }

    /**
     * prepend filter chain
     *
     */
    function prependFilterChain()
    {
        // Here we call a filter action of the common module to prevent browser caching
        // $this->model->CommonFilterNoCache();    
    }

    /**
     * append filter chain
     *
     */
    function appendFilterChain( & $outputBuffer )
    {
        // Here we call a filter action of the common module that trims the html output
        // $this->model->CommonFilterTrim( $outputBuffer );        
    }
}

?>