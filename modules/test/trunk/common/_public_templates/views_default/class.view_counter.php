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
 * view_counter class
 *
 */
 
class view_counter extends view
{
     /**
     * Default template
     * @var string $template
     */
    var $template = 'counter';
    
    /**
     * Execute the view of the template "templates_default/tpl.counter.php"
     * create the template variables
     *
     * @return bool true on success else false
     */
    function perform()
    {
         /* Counter 1,2,3 Event calls. 
         See: modules/test/actions/class.action_test_counter.php  
         The results are printed out in the template "templates_default/tpl.counter.php" .*/          
         M( MOD_TEST, 
            'counter', 
            array('var'           => 'tpl_counter1', 
                  'start_counter' => 0, 
                  'end_counter'   => 200)); 
                  
         M( MOD_TEST, 
            'counter', 
            array('var'           => 'tpl_counter2', 
                  'start_counter' => 1000, 
                  'end_counter'   => 1200));
                  
         M( MOD_TEST, 
            'counter', 
            array('var'           => 'tpl_counter3', 
                  'start_counter' => 10000, 
                  'end_counter'   => 10200));  
                  
        return TRUE;
    }    
}

?>
