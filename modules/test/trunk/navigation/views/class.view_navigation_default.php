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
 * view_navigation_default class of the template "tpl.navigation_default.php"
 *
 */
 
class view_navigation_default extends view
{
   /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'navigation_default';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/navigation/templates/';
        
   /**
     * Execute the view of the template "tpl.navigation_default.php"
     *
     * @return bool true
     */
    function perform()
    {
        // move up or down a node
        if( isset($_GET['dir']) )
        {
            M( MOD_NAVIGATION, 
               'sort_node', 
               array('node' => $_GET['dir_node'],
                     'dir'  => $_GET['dir']));        
        }
        
        if(!isset($_GET['node']))
        {
            $node = 0;
        }
        else
        {
            $node = (int)$_GET['node'];
        }
        
        // assign the template array $B->tpl_nodes with navigation nodes
        M( MOD_NAVIGATION, 
           'get_childs', 
           array('result' => 'tpl_nodes',
                 'node'   => $node));
                 
        // assign the template array $B->tpl_nodes with navigation nodes
        M( MOD_NAVIGATION, 
           'get_branch', 
           array('result'     => 'tpl_branch',
                 'node_title' => 'tpl_node_title',
                 'node'       => $node));                 
           
        return TRUE;
    }   
}

?>