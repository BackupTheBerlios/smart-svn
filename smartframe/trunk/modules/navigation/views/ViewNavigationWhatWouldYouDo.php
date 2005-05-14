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
 * ViewUserMain class
 *
 */

class ViewNavigationWhatWouldYouDo extends SmartView
{
     /**
     * Template for this view
     * @var string $template
     */
    public $template = 'whatWouldYouDo';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/navigation/templates/';
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        // init users template variable 
        $this->tplVar['navigation'] = array();   
        
        // add links which are finaly displayed
        // at the main admin page
        $this->tplVar['navigation']['wwyd'][] = array('link' => '?mod=navigation&view=addnode',
                                                      'text' => 'Add navigation node');
    }     
}

?>