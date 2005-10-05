<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ViewDefaultSystemInfo class
 *
 */

class ViewDefaultSystemInfo extends SmartView
{
     /**
     * Template for this view
     * @var string $template
     */
    public $template = 'systemInfo';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/default/templates/';
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        $this->tplVar['phpVersion'] = phpversion();
        $this->tplVar['mysqlInfo'] = array();

        $this->model->action('common','mysqlInfo', 
                             array('result' => &$this->tplVar['mysqlInfo']));
    }     
}

?>