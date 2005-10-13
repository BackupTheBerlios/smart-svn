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
        $this->tplVar['navigation']['wwyd'][] = array('link' => '?mod=navigation&view=addNode',
                                                      'text' => 'Add navigation node');
    }     
}

?>