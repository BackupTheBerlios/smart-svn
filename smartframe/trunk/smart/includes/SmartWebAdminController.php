<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/*
 * Web controller class
 *
 */
class SmartWebAdminController extends SmartController
{
    /**
     * View factory object
     *
     * @var object $view
     */
    private $view;  
    
    /**
     * Dispatch the request.
     *
     */
    public function dispatch()
    {
        // parent view class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartView.php' );
        // Include view parent factory class and its child class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartViewFactory.php' );
        include_once( SMART_BASE_DIR . 'smart/includes/SmartAdminViewFactory.php' );

        try
        {
            // create a view factory instance
            // this instance aggregates the model object
            $this->view = new SmartAdminViewFactory( $this->model );

            // Build the view methode name of the "index" view of the "common" module
            $methode = ucfirst( SMART_COMMON_MODULE ) . 'Index';
            
            // Execute the index view of a common module
            $this->view->$methode();
        }
        catch(SmartViewException $e)
        {
            $e->performStackTrace(); 
            $this->userErrorView();
        }
        catch(SmartModelException $e)
        {
            $e->performStackTrace();
            $this->userErrorView();
        }         
        catch(SmartTplException $e)
        {
            $e->performStackTrace(); 
            $this->userErrorView();
        }         
        
        ob_end_flush();
    }
    
    /**
     * Web user error view is executed if an exception arrise
     *
     */    
    private function userErrorView()
    {
        $methode = ucfirst( SMART_COMMON_MODULE ) . 'Error';
        $this->view->$methode( array('error' => 'Unexpected error. Please contact the administrator') );    
    }     
}

?>
