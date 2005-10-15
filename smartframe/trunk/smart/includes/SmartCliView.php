<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Parent class of every view class
 *
 */

class SmartCliView extends SmartObject
{
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
     * The session object
     * @var object $session
     */
    public $session;
    
     /**
     * Smart main configuration array
     * @var array $config
     */
    public $config;   

     /**
     * Default error view
     * @var string $error_view
     */
    public $errorView = 'cliError';

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
     * constructor
     *
     */
    public function __construct()
    {
    }

    /**
     * perform
     *
     */
    public function perform()
    {
    }

    /**
     * authentication
     *
     */
    public function auth()
    {
    }

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
    }
}

?>
