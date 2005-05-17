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
 * Web Admin controller class
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
            // this instance aggregates the view factory object
            $this->view = new SmartAdminViewFactory( $this->model, $this->config );

            // Build the view methode name of the "index" view of the "common" module
            $methode = ucfirst( $this->config['base_module'] ) . 'Index';

            // run broadcast action init event to every module
            $this->model->broadcast( 'init' );
            
            // Execute the index view of a common module
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
        catch(SQLException $e)
        {          
            $this->setExceptionFlags( $e );  
            SmartExceptionLog::log( $e );   
            $this->userErrorView();
        }        
        catch(SmartForwardAdminViewException $e)
        {
            // run broadcast action init event to every module if demanded
            if(TRUE == $e->broadcast)
            {
                $this->model->broadcast( 'init' );
            }
            $this->view->{$e->view}($e->data, $e->constructorData);  
        }      

        ob_end_flush();
    }
    
    /**
     * Web user error view is executed if an exception arrise
     *
     */    
    private function userErrorView()
    {
        $methode = ucfirst( $this->config['base_module'] ) . 'Error';
        $this->view->$methode();    
    }     
}

?>
