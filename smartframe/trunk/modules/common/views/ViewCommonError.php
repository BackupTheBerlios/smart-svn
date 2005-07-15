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
 * ViewError class
 */

class ViewCommonError extends SmartView
{
     /**
     * Template of this view
     * @var string $template
     */
    public $template = 'error';

     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/common/templates/';

    /**
     * The end user error view.
     *
     */
    function perform()
    {
        // assign template error var
        $this->tplVar['error'] = & $this->viewData;
    }
}

?>