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