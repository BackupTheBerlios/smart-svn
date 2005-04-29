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
class SmartWebController extends SmartController
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
     * Web Controller construct
     *
     * Call parent constructor and validate view request
     */
    public function __construct ()
    {
        // parent construct run the base init process common to all controllers
        parent::__construct();
    }
    
    /**
     * Dispatch the request.
     *
     */
    public function dispatch()
    {
        // parent view class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartView.php' );
        // Include view runner parent and its child class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartViewFactory.php' );
        include_once( SMART_BASE_DIR . 'smart/includes/SmartPublicViewFactory.php' );

        // create a viewRunner instance
        // this instance aggregates the model object
        $this->view = new SmartPublicViewFactory( $this->model );

        // set the public view folder
        $this->view->viewFolder = $this->model->getConfigVar( 'common', 'publicViewFolder' );

        // get view request
        if( !isset($_REQUEST['view']) || empty($_REQUEST['view']) )
        {
            $this->viewRequest = SMART_DEFAULT_VIEW;
        }
        else
        {
            $this->validateViewName( $_REQUEST['view'] );
        }

        // build the view methode
        $methode = ucfirst($this->viewRequest);
        
        // Launch the view
        if( FALSE == $this->viewRequestError )
        {
            $this->view->$methode();
        }
        else
        {
            $this->view->$methode( array('error' => $this->viewRequestError) );
        }
        ob_end_flush();
    }
    /**
     * Validate view request name.
     *
     * @see __construct() 
     */
    private function validateViewName( $view_name )
    {
        if(preg_match("/[^a-zA-Z0-9_]/", $view_name))
        {
            $this->viewRequest      = 'error';
            $this->viewRequestError = 'Wrong view fromat: ' . $view_name;
            return;
        }

        if(!@file_exists(SMART_BASE_DIR . 'views_' . $this->view->viewFolder . '/SmartView' . $view_name . '.php'))
        {
            $this->viewRequest      = 'error';
            $this->viewRequestError = 'View dosent exists: ' . SMART_BASE_DIR . 'views_' . $view_folder . '/view.' . $view_name . '.php';
            return;
        }

        $this->viewRequest  = $view_name;
        return;
    }
}

?>
