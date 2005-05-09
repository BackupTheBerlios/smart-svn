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
 * Parent class of every view class
 *
 */

class SmartView extends SmartObject
{
    /**
     * Template variable container
     * @var mixed $tplVar
     */
    public $tplVar = FALSE;

    /**
     * View variable container
     * @var object $viewVar
     */
    public $viewVar = FALSE;

    /**
     * The model object
     * @var object $model
     */
    public $model;
    
     /**
     * Smart main configuration array
     * @var array $config
     */
    public $config;   

     /**
     * Default error view
     * @var string $error_view
     */
    public $errorView = 'error';

     /**
     * Template related to this view
     * @var string $template
     */
    public $template = '';

     /**
     * Template render flag
     * @var bool $render_template
     */
    public $renderTemplate = TRUE;

     /**
     * Template folder
     * @var bool $template_folder
     */
    public $templateFolder = FALSE;

     /**
     * Data container passed to the view
     * @var mixed $view_data
     */
    public $viewData = FALSE;

     /**
     * Cache expire time
     * @var int $cacheExpire 0 = cache disabled
     */
    public $cacheExpire = 0;

    /**
     * constructor
     *
     */
    function __construct()
    {
    }

    /**
     * perform
     *
     */
    function perform()
    {
    }

    /**
     * authentication
     *
     */
    function auth()
    {
    }

    /**
     * prepend filter chain
     *
     */
    function prependFilterChain()
    {
    }

    /**
     * append filter chain
     *
     */
    function appendFilterChain( & $tplBufferContent )
    {
    }
}

?>
