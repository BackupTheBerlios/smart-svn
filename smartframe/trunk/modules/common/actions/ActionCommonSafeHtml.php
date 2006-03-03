<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ActionCommonSafeHtml
 *
 */

/**
 * 
 */
class ActionCommonSafeHtml extends SmartAction
{
    /**
     * strip bad code from string
     *
     * @param mixed $data Data passed to this action
     */
    public function perform( $data = FALSE )
    {
        if(!defined('XML_HTMLSAX3'))
        {
            define('XML_HTMLSAX3', SMART_BASE_DIR . 'modules/common/includes/safehtml/');
            include_once(SMART_BASE_DIR . 'modules/common/includes/safehtml/safehtml.php');
        }
        
        $this->model->safehtml = new safehtml();
        return $this->model->safehtml->parse( $data);
    }  
}

?>