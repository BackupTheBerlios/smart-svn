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
 * view_index class of the template "group_index.tpl.php"
 *
 */
 
class view_index extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'index';
    
    /**
     * Execute the view of the template "group_index.tpl.php"
     *
     * @return mixed (object) this object on success else (bool) false on error
     */
    function & perform()
    {
        // nothing to do. just return this object
        return $this;
    }    
}

?>
