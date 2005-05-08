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
        include_once( SMART_BASE_DIR . 'smart/includes/SmartPublicViewFactory.php' );

        try
        {
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
            $methode = $this->validateViewName( $viewRequest );
        
            // execute the requested view
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
        catch(SmartCacheException $e)
        {
            $e->performStackTrace(); 
            $this->userErrorView();
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
            throw new SmartViewException('Wrong view fromat: ' . $view_name, SMART_VIEW_ERROR);
        }

        if(!@file_exists(SMART_BASE_DIR . $this->view->viewFolder . '/View' . ucfirst($view_name) . '.php'))
        {
            throw new SmartViewException('View dosent exists: ' . SMART_BASE_DIR . $this->view->viewFolder . '/View' . ucfirst($view_name) . '.php', SMART_VIEW_ERROR);
        }

        return $view_name;
    }

    /**
     * Web user error view is executed if an exception arrise
     *
     */    
    private function userErrorView()
    {
        $methode = SMART_ERROR_VIEW;
        $this->view->$methode( array('error' => 'Unexpected error. Please contact the administrator') );    
    }    
}

?>
