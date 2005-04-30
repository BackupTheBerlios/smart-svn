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
     * Name of the view
     *
     * @var string $viewReques
     */
    private $viewRequest;

    /**
     * Error string when an view error occurs
     *
     * @var string $viewRequestError
     */    
    private $viewRequestError = FALSE;    
    
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

        // create a view factory instance
        // this instance aggregates the model object
        $view = new SmartAdminViewFactory( $this->model );

        // Build the view methode name of the "index" view of the "common" module
        $methode = ucfirst( SMART_COMMON_MODULE ) . 'Index';
        
        // execute this view
        if(FALSE == $view->$methode())
        {
            $methode = ucfirst( SMART_COMMON_MODULE ) . 'Error';
            $view->$methode( array('error' => 'Unexpected error. Please check log files') );        
        }
       
        ob_end_flush();
    }
}

?>
