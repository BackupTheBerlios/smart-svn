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
 * ActionCommonFilterTrim
 *
 */

class ActionCommonFilterTrim extends SmartAction
{
    /**
     * Add http headers to disable browser caching
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $data['str'] = trim( $data['str'] ); 
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        return TRUE;
    }    
}

?>