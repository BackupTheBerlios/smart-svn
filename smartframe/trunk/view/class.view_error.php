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
 * default error view
 *
 */
 
class view_error extends view
{
     /**
     * Default template
     * @var string $template
     */
    var $template = 'error';

    /**
     * Perform on the error view
     *
     * @param object $view_obj Object of the view from which the error occurs
     * @return bool true 
     */
    function perform( & $view_obj )
    {
        // assign template error var
        $this->B->view_error = nl2br( $view_obj->getError() );

        return TRUE;
    }    
}

?>
