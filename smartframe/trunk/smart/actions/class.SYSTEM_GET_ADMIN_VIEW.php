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
 * SYSTEM_GET_ADMIN_VIEW class 
 *
 */
 
class SYSTEM_GET_ADMIN_VIEW
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function SYSTEM_GET_ADMIN_VIEW()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * - check if the main admin template exists
     * - return the template path
     *
     *
     * @param array $data
     * @return string
     */
    function perform( $data )
    {
        // path to the main admin template
        $tpl = SF_BASE_DIR . 'modules/' .SF_COMMON_MODULE. '/templates/index.tpl.php';
    
        if(!file_exists( $tpl ))
        {
            die('Missing main admin template: ' . $tpl);
        }

        return $tpl;
    }    
}

?>
