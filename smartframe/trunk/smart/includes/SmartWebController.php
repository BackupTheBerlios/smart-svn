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
            // run broadcast action init event to every module
            $this->model->broadcast( 'init' );

            // create a viewRunner instance
            // this instance aggregates the model object
            $this->view = new SmartPublicViewFactory( $this->model, $this->config );

            // get the view which is associated with a request
            $viewRequest = '';
            
            if(isset($_REQUEST[$this->config['view_request_id_name']]))
            {
                $this->model->action( $this->config['view_request_module'],
                                      'relatedView'
                                      array('id'     => $_REQUEST[$this->config['view_request_id_name']],
                                            'result' => &$viewRequest));
            }
            
            // get view request
            if( empty($viewRequest) )
            {
                if( isset($_REQUEST['view']) )
                {
                    $viewRequest = $_REQUEST['view'];
                } 
                else
                {
                    $viewRequest = $this->config['default_view'];
                }
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
            $this->userErrorView( $e->getMessage() );
        }
        catch(SmartModelException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace();
            $this->userErrorView( $e->getMessage() );
        }         
        catch(SmartTplException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorView( $e->getMessage() );
        } 
        catch(SmartCacheException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorView( $e->getMessage() );
        } 
        catch(SmartDbException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            $this->userErrorView( $e->getMessage() );
        }            
        catch(SmartForwardPublicViewException $e)
        {
            $this->view->{$e->view}($e->data, $e->constructorData);  
        }        
        catch(SmartForwardAdminViewException $e)
        {
            die('Setup not yet done. Please run the admin web controller');
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
            throw new SmartViewException('View class dosent exists: ' . SMART_BASE_DIR . $this->model->config['views_folder'] . '/View' . ucfirst($view_name) . '.php');
        }

        return $view_name;
    }

    /**
     * Web user error view is executed if an exception arrise
     *
     */    
    private function userErrorView( $message )
    {
        $methode = $this->config['error_view'];
        
        try
        {        
            $this->view->$methode( $message );    
        }
        catch(SmartViewException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace();
            die('Fatal View Error');
        }
        catch(SmartModelException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace();
            die('Fatal Model Error');
        }         
        catch(SmartTplException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            die('Fatal Template Error');
        } 
        catch(SmartCacheException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            die('Fatal Cache Error');
        } 
        catch(SmartDbException $e)
        {
            $this->setExceptionFlags( $e );
            $e->performStackTrace(); 
            die('Fatal DB Error');
        }     
    }    
}

?>
