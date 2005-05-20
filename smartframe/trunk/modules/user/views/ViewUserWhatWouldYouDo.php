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

class ViewUserWhatWouldYouDo extends SmartView
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
    public $templateFolder = 'modules/user/templates/';

    /**
     * prepend filter chain
     *
     */
    function prependFilterChain()
    {
        // check permission to execute this view
        if(FALSE == $this->checkViewPermission())
        {
            $this->renderTemplate = FALSE;
        }    
    }
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        // stop if no template to render; means no rights
        if($this->renderTemplate == FALSE)
        {
            return;
        }
        
        // init users template variable 
        $this->tplVar['user'] = array();   
        
        $this->tplVar['user']['wwyd'][] = array('link' => '?mod=user&view=adduser',
                                                'text' => 'Add user');
    }     
    
    /**
     * Check permission to execute this view
     * @return bool
     */
    private function checkViewPermission()
    {
        if($this->viewVar['loggedUserRole'] < 60)
        {
            return TRUE;
        }
        return FALSE;
    }    
}

?>