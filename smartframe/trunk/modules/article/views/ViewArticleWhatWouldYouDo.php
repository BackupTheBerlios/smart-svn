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
 * ViewArticleWhatWouldYouDo class
 *
 */

class ViewArticleWhatWouldYouDo extends SmartView
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
    public $templateFolder = 'modules/article/templates/';
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        // init article template variable 
        $this->tplVar['article'] = array();   
        
        // add articles which are finaly displayed
        // at the main admin page
        $this->tplVar['article']['wwyd'][] = array('article' => '?mod=article&view=addArticle&disableMainMenu=1',
                                                   'text'    => 'Add Article');
    }     
}

?>