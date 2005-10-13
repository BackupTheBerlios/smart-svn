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
 * ActionCommonFilterTrim
 *
 * USAGE:
 * $model->action( 'common', 'filterTrim', 
 *                 array('str' => & (string) );
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
        if(!is_string($data['str']))
        {
            throw new SmartModelException("'str' isnt from type string");
        }    
        return TRUE;
    }    
}

?>