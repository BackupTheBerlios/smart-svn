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
 * view_validate class of the template "group_validate.tpl.php"
 *
 */
 
class view_validate extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'validate';
    
    /**
     * Execute the view of the template "group_validate.tpl.php"
     *
     * @return mixed (object) this object on success else (bool) false on error
     */
    function perform()
    {
        $this->B->tpl_is_valid = $this->B->M( MOD_USER, 
                                              'validate',
                                              array('md5_str' => $_GET['usr_id']));
                                              
        if(TRUE === $this->B->tpl_is_valid)
        {
            $this->B->tpl_validation_message = 'Your account is now active.';
        }
        else
        {
            $this->B->tpl_validation_message = 'Account activation fails!!!';
        }
        
        return $this;
    }    
    
}

?>
