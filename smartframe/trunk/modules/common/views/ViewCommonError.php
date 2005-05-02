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
     * Fetch error string
     *
     */
    function perform()
    {
        // assign error as template variable
        // 
        $this->tplVar = $this->viewData;
    }
}

?>