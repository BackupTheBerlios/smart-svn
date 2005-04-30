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
     * Dispatch the request.
     *
     */
    public function dispatch()
    {
        // parent view class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartView.php' );
        // Include view parent factory class and its child class
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
            $viewRequest = SMART_DEFAULT_VIEW;
        }
        else
        {
            $viewRequest = $_REQUEST['view'];
        }

        // validate view request
        if(FALSE == ($methode = $this->validateViewName( $viewRequest )))
        {
            $methode = SMART_ERROR_VIEW;
            $this->view->$methode( array('error' => $this->viewRequestError) );
            ob_end_flush();
            exit;
        }
        
        // Launch the view
        if( FALSE == $this->view->$methode() )
        {
            $methode = SMART_ERROR_VIEW;
            $this->view->$methode( array('error' => 'Unexpected error. Please check log files') );
        }
        ob_end_flush();
    }
    /**
     * Validate view request name.
     *
     * @see dispatch() 
     */
    private function validateViewName( $view_name )
    {
        if(preg_match("/[^a-zA-Z0-9_]/", $view_name))
        {
            $this->viewRequestError = 'Wrong view fromat: ' . $view_name;
            return FALSE;
        }

        if(!@file_exists(SMART_BASE_DIR . $this->view->viewFolder . '/View' . ucfirst($view_name) . '.php'))
        {
            $this->viewRequestError = 'View dosent exists: ' . SMART_BASE_DIR . $this->view->viewFolder . '/view.' . $view_name . '.php';
            return FALSE;
        }

        return $view_name;
    }
}

?>
