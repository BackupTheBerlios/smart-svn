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
 * default error view
 * You can change this class to your needs. It is only a proposition on
 * how to handle view errors.
 *
 */
 
class view_error extends view
{
     /**
     * Default template
     * @var string $template
     */
    var $template = 'error';

    /**
     * Perform on the error view
     *
     * If some errors occure you will find them in $this->view_data['error']
     *
     * @return bool true 
     */
    function perform()
    {
        // SF_DEBUG is defined in /smart/includes/defaults.php
        if( SF_DEBUG == TRUE )
        {
            // template error array
            $this->B->tpl_error = array();
            
            if( is_array($this->view_data['error']) && count($this->view_data['error'] > 0))
            {
                $this->B->tpl_error = & $this->view_data['error'];
            }
            else
            {
                $this->B->tpl_error = array('unexpected Error' => 'Check log file');
            }
            // Stop if there are circular errors
            if($this->B->circular_error_counter++ == 1)
            {
                die('Circular error in : ' . __FILE__);
            }            
        }
        // In non debug mode, load index view on error and trigger the error
        else
        {
            // Stop if there are circular errors
            if($this->B->circular_error_counter++ == 1)
            {
                die('Circular error in : ' . __FILE__);
            }
            
            // get error array as string
            ob_start();
            print_r( $this->view_data );
            $error = ob_get_clean();
            
            trigger_error( $error, E_USER_WARNING);
            
            // load index view
            M( MOD_SYSTEM, 'get_view', array('view' => 'index'));  
            exit;
        }

        return TRUE;
    }    
}

?>
