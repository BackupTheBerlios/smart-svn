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
 * ViewMainNavigationclass
 */

class ViewMainNavigation extends SmartView
{
    /**
     * Execute the view of the "mainNavigation" template
     */
    function perform()
    {
        // get 'active' root navigation nodes. Means nodes with id_parent = 0
        // we need the node titles and id_nodes
        // the result is stored in an template variable $tpl['rootNodes']
        // status 1=inactive  2=active
         
         $this->tplVar['rootNodes'] = array(); 
         // get child nodes that have id_node = 0 as id_parent
         $this->model->action( 'navigation', 'getChilds', 
                               array('id_node' => 0,
                                     'result'  => & $this->tplVar['rootNodes'],
                                     'status'  => array('=', 2),
                                     'fields'  => array('title','id_node'))); 
                                     
        // get text for the page footer
        $this->tplVar['footer'] = array();
        $this->model->action('misc','getText', 
                             array('id_text' => 3,
                                   'result'  => & $this->tplVar['footer'],
                                   'fields'  => array('body')));                                       
    }
}

?>