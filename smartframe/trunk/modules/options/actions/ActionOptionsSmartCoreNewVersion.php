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
 * ActionOptiuonsSmartCoreNewVersion
 *
 * Delete public views cache
 *
 * USAGE:
 * $model->action( 'options', 'smartCoreNewVersion', 
 *                 array('new_version' => (string) );
 *
 */

class ActionOptionsSmartCoreNewVersion extends SmartAction
{
    /**
     * Add http headers to disable browser caching
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $this->model->action( 'options','deletePublicCache');
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!is_string($data['new_version']))
        {
            throw new SmartModelException("'new_version' isnt from type string");
        }    
        return TRUE;
    }    
}

?>