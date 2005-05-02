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

/**
 * Public view factory.
 *
 */

class SmartPublicViewFactory extends SmartViewFactory
{
    /**
     * dynamic call of public view objects
     *
     * @param string $view View name
     * @param array $args Arguments passed to the view. 
     * $args[0] is aggregated by the view object > $view->viewVar
     * $args[1] passed to the constructor
     * $args[2] bool true = force a new instance
     */
    public function __call( $view, $args )
    {
        // build the whole view class name
        $requestedView = 'View'.ucfirst($view);

        if( !isset( $args[0] ) ) 
        {
            $args[0] = NULL;
        }
        if( !isset( $args[1] ) ) 
        {
            $args[1] = NULL;
        }
        if( !isset( $args[2] ) ) 
        {
           $args[2] = NULL;
        } 

        if( !isset($this->$requestedView) || ($args[2] == TRUE) )
        {
            // path to the modules view class
            $class_file = SMART_BASE_DIR . $this->viewFolder .'/'.$requestedView.'.php';

            if(@file_exists($class_file))
            {
                include_once($class_file);

                // force a new instance
                if( $args[2] == TRUE )
                {
                    $i = 1;
                    $requestedView = $requestedView . $i;
                    while( isset($this->$requestedView) )
                    {
                        $i++;
                        $requestedView = $requestedView . $i;
                    }
                    // make new instance of the module view class
                    $this->$requestedView = new $requestedView( $args[1] );
                }
                else
                {
                    // make instance of the module view class
                    $this->$requestedView = new $requestedView( $args[1] );
                }
            }
            else
            {
                throw new SmartViewException("View dosent exists: ".$class_file, SMART_NO_VIEW);
            }
        }
           
        // alias to the view object
        $view = $this->$requestedView;
            
        // Aggregate model object
        $view->model = $this->model;

        // Aggregate view loader object
        //$view->viewLoader = $this;
           
        // include template container
        if( $view->renderTemplate == SMART_TEMPLATE_RENDER )
        {
            // parent template container class
            include_once( SMART_BASE_DIR . 'smart/includes/SmartTplContainer.php' );
            // get template container object
            $tplContainer = SmartContainer::newInstance( SMART_TEMPLATE_ENGINE );
            // aggregate this object
            $tplContainer->viewLoader = $this;
            // aggregate this container variable to store template variables
            $view->tplVar = & $tplContainer->vars; 
        }

        // Aggregate view container object
        $viewContainer = SmartContainer::newInstance('SmartViewContainer');
        // use this to pass variables inside the view(s)
        $view->viewVar = & $viewContainer->vars;

        // pass parameter data to the view
        $view->viewData = $args[0];
            
        // run authentication
        $view->auth();
            
        // run view prepended filters
        $view->prependFilterChain();
            
        // perform on the main job
        $view->perform();
            
        // render a template if needed
        if ( SMART_TEMPLATE_RENDER == $view->renderTemplate )
        {
            // set template name
            $tplContainer->template = $view->template;
                
            // which template folder to use?
            if( $view->templateFolder != FALSE )
            {
                $tplContainer->templateFolder = $view->templateFolder;
            }
            else if(!defined('SMART_TPL_FOLDER'))
            {
                $tplContainer->templateFolder = $this->model->getConfigVar('common', 'publicTemplateFolder');
            }
            else if(defined('SMART_TPL_FOLDER'))
            { 
                $tplContainer->templateFolder = SMART_TPL_FOLDER;
            }                
                
            // render the template
            $tplContainer->renderTemplate();
        }                
            
        // run append filters
        $view->appendFilterChain( $tplContainer->tplBufferContent );
            
        // echo the context
        echo $tplContainer->tplBufferContent;                
    } 
}

?>