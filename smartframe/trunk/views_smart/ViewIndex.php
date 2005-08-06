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
 * ViewIndex class
 */

class ViewIndex extends SmartView
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Execute the view of the "index" template
     */
    public function perform()
    {
        // template var with charset used for the html page
        $this->tplVar['charset'] = & $this->config['charset'];
        $this->tplVar['text']    = array();

        // depended of the client browser language
        // the following action fetch the german or english text
        // of the front page
        if($this->getBrowserLang() == 'en')
        {
            $id_text = 4;
        }
        else
        {
            $id_text = 1;
        }

        // get text for the front page
        $this->model->action('misc','getText', 
                             array('id_text' => $id_text,
                                   'result'  => & $this->tplVar['text'],
                                   'fields'  => array('body')));  
                                                          
        return TRUE;
    }

    /**
     * authentication
     *
     */
    public function auth()
    {
        // Check if the visitor is a logged user
        //
        if(NULL == ($this->viewVar['loggedUserId'] = $this->model->session->get('loggedUserId')))
        {
            $this->tplVar['isUserLogged'] = FALSE; 
        }
        else
        {
            $this->tplVar['isUserLogged'] = TRUE;
        }
        $this->viewVar['loggedUserRole'] = $this->model->session->get('loggedUserRole');     
    }

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // filter action of the common module to prevent browser caching
        $this->model->action( 'common', 'filterDisableBrowserCache');    
    }

    /**
     * append filter chain
     *
     */
    public function appendFilterChain( & $outputBuffer )
    {
        // filter action of the common module that trims the html output
        $this->model->action( 'common', 'filterTrim', array('str' => & $outputBuffer) );        
    }
    
    /**
     * check if clients browser lang is 'de' else return 'en'
     *
     * @return string
     */    
    private function getBrowserLang()
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            preg_match_all('/([a-z]*)/',$_SERVER['HTTP_ACCEPT_LANGUAGE'], $blang, PREG_SET_ORDER);
            if( $blang[0][1] == 'de' )
            {
                return $blang[0][1];
            }
        }
        return 'en';
    }
}

?>