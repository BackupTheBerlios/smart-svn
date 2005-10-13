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
 * ViewIndex class
 *
 * The parent variables are:
 * $tplVar  - Array that may contains template variables
 * $viewVar - Array that may contains view variables, which
 *            are needed by some followed nested views.
 * $model   - The model object
 *            We need it to call modules actions
 * $template - Here you can define an other template name as the default
 * $renderTemplate - Is there a template associated with this view?
 *                   SMART_TEMPLATE_RENDER or SMART_TEMPLATE_RENDER_NONE
 * $viewData - Data passed to this view by the caller
 * $cacheExpire - Expire time in seconds of the cache for this view. 0 means cache disabled
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
        // template var with charset used for the html pages
        $this->tplVar['charset'] = & $this->config['charset'];
        // relative path to the smart directory
        $this->tplVar['relativePath'] = SMART_RELATIVE_PATH;
        
        // we need this template vars to show admin links if the user is logged
        $this->tplVar['loggedUserRole']      = $this->viewVar['loggedUserRole'];
        $this->tplVar['adminWebController']  = $this->config['admin_web_controller']; 
        
        $this->tplVar['text']    = array();

        // depended of the client browser language
        // the following action fetch the german or english text
        // of the front page
        $id_text = $this->getLangRelatedIDtext();

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
    /**
     * get language related text ID - 1='en' 2='de' 5='fr'
     *
     * @return int
     */       
    private function getLangRelatedIDtext()
    {
        if(isset($_GET['lang']))
        {
            if($_GET['lang'] == 'de')
            {
                $this->model->session->set('lang','de');
                $this->tplVar['lang'] = 'de';
                return 1;
            }
            elseif($_GET['lang'] == 'fr')
            {
                $this->model->session->set('lang','fr');
                $this->tplVar['lang'] = 'fr';
                return 5;
            }            
            else
            {
                $this->model->session->set('lang','en');
                $this->tplVar['lang'] = 'en';
                return 4;
            }
        }    
        elseif(NULL !== ($lang = $this->model->session->get('lang')))
        {
            if($lang == 'de')
            {
                $this->tplVar['lang'] = 'de';
                return 1;
            }
            elseif($lang == 'fr')
            {
                $this->tplVar['lang'] = 'fr';
                return 5;
            }            
            else
            {
                $this->tplVar['lang'] = 'en';
                return 4;
            }
        }
        elseif(($lang = $this->getBrowserLang()) == 'de')
        {
            $this->tplVar['lang'] = 'de';
            return 1;
        }
        elseif($lang == 'fr')
        {
            $this->tplVar['lang'] = 'fr';
            return 5;
        }        
        else
        {
            $this->tplVar['lang'] = 'en';
            return 4;
        }    
    }
}

?>