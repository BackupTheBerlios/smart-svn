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
 * ViewNodePicture class
 */

class ViewNodePicture extends SmartView
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Html template associated with this view
     */
    public $template = 'nodePicture';
    
    /**
     * Execute the view of the "nodePicture" template
     */
    function perform()
    { 
        // template var with charset used for the html pages
        $this->tplVar['charset'] = & $this->config['charset'];
        
        // init template 'pic' variable 
        $this->tplVar['pic'] = array();
        
        // get requested picture content
        $this->model->action('navigation','getPicture', 
                             array('result' => & $this->tplVar['pic'],
                                   'id_pic' => $this->current_id_pic,
                                   'fields' => array('title','description',
                                                     'file','size','width','height',
                                                     'mime','media_folder')));
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
        // fetch the current id_pic. If no id_pic defined or not numeric
        // this view class loads the error template
        if( !isset($_REQUEST['id_pic']) || preg_match("/[^0-9]+/",$_REQUEST['id_pic']) ) 
        {
            $this->template  = 'error';     
        }
        else
        {
            $this->current_id_pic    = (int)$_REQUEST['id_pic'];          
        }
        
        // filter action of the common module to prevent browser caching
        $this->model->action( 'common', 'filterDisableBrowserCache');    
    }
}

?>