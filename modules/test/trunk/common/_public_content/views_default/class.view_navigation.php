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
 * view_navigation class. Main navigation view
 *
 * Here we dont need authentication, prepend or append filters
 * since this view is included in other templates, which do this job
 *
 */
 
class view_navigation extends view
{
     /**
     * Default template
     * @var string $template
     */
    var $template = 'navigation';

    /**
     * Execute the view of the template "templates_xxx/tpl.navigation.php"
     * create template variables
     *
     * @return bool true on success else false
     */
    function perform()
    {
         /* Event to get the main navigation menu entries, with status "publish" from the navigation module action class. 
         See: modules/navigation/actions/class.action_navigation_get_childs.php 
         The array $B->tpl_nodes containjs the top level navigation nodes. 
         
         status 1=drawt  2=public

         */
         M( MOD_NAVIGATION, 
            'get_childs', 
            array('result' => 'tpl_nodes',
                  'status' => 2));
        
        return TRUE;
    }    
}

?>