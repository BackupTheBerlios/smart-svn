<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
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
    function perform( $view_obj = FALSE )
    {
        if (is_object( $view_obj ))
        {
            // assign template error var
            $this->B->view_error = nl2br( $view_obj->getError() );
        }

        return TRUE;
    }    
}

?>
