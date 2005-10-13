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
 * ActionCommonFilterDisableBrowserCache
 *
 * USAGE:
 * $model->action( 'common', 'filterDisableBrowserCache');    
 *
 */

class ActionCommonFilterDisableBrowserCache extends SmartAction
{
    /**
     * Add http headers to disable browser caching
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('P3P: CP="NOI NID ADMa OUR IND UNI COM NAV"');
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