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
        if(!@file_exists($this->config['config_path'] . 'dbConfig.php'))
        {
            die('Setup not yet done.');
        }
        
        // parent view class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartView.php' );
        // Include view parent factory class and its child class
        include_once( SMART_BASE_DIR . 'smart/includes/SmartViewFactory.php' );
        include_once( SMART_BASE_DIR . 'smart/includes/SmartPublicViewFactory.php' );

        try
        {
            // run broadcast action init event to every module
            $this->model->broadcast( 'init' );

            // create a viewRunner instance
            // this instance aggregates the model object
            $this->view = new SmartPublicViewFactory( $this->model, $this->config );

            // get view request
            if( !isset($_REQUEST['view']) || empty($_REQUEST['view']) )
            {
                $viewRequest = $this->config['default_view'];
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
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorView();
        }
        catch(SmartModelException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace();
            $this->userErrorView();
        }         
        catch(SmartTplException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorView();
        } 
        catch(SmartCacheException $e)
        {
            $this->setExceptionFlags( $e );
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
            throw new SmartViewException('Wrong view fromat: ' . $view_name);
        }

        if(!@file_exists(SMART_BASE_DIR . $this->model->config['views_folder'] . '/View' . ucfirst($view_name) . '.php'))
        {
            throw new SmartViewException('View dosent exists: ' . SMART_BASE_DIR . $this->model->config['views_folder'] . '/View' . ucfirst($view_name) . '.php');
        }

        return $view_name;
    }

    /**
     * Web user error view is executed if an exception arrise
     *
     */    
    private function userErrorView()
    {
        $methode = $this->config['error_view'];
        $this->view->$methode( array('error' => 'Unexpected error. Please contact the administrator') );    
    }    
}

?>
