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
 * TEST_COUNTER class 
 *
 */
 
class TEST_COUNTER
{
    /**
     * Fill up an array with (counter) numbers
     *
     * Structure of the $data array:
     * $data['var']           - array name where to store numbers
     * $data['start_counter'] - counter starting number
     * $data['end_counter']   - counter end number
     *
     * @param array $data
     */
    function perform( $data )
    {
            // get var name defined in the public template to store the result
            $_result = & $GLOBALS['B']->$data['var']; 
            
            // the result must be an array
            $_result = array();
            
            // check if start/end counter are defined else set default values
            if(empty($data['start_counter']))
                $data['start_counter'] = 0;
            if(empty($data['end_counter']))
                $data['end_counter'] = 10;
                
            // assign counter vars
            for($i = $data['start_counter']; $i <= $data['end_counter']; $i++)
                $_result[] = $i;    
    }    
}

?>
