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
 * earchive_have_access class 
 *
 */
 
class earchive_have_access
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
    function earchive_have_access()
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
     * Check if registered user is required to access list with status 3
     *
     * @param array $data
     */
    function perform( & $data )
    {
        /* get list status */
        M( MOD_EARCHIVE, 
           'get_list', 
           array( 'lid'    => $data['lid'],       
                  'var'    => 'tmp_list', 
                  'fields' => array('status'))); 
    
        if( ($this->B->tmp_list['status'] == 3) && ($this->B->is_logged === FALSE) )
        {
                $query = base64_encode($this->_getQueryString());
                @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?view=login&url='.$query);
                exit;      
        }    
        return TRUE;     
    } 
    
    /**
     * Returns the correct link for the back/pages/next links
     *
     * @return string Url
     */
    function _getQueryString()
    {
        // Sort out query string to prevent messy urls
        $querystring = array();
        $qs = array();
        if (!empty($_SERVER['QUERY_STRING'])) {
            $qs = explode('&', str_replace('&amp;', '&', $_SERVER['QUERY_STRING']));
            for ($i=0, $cnt=count($qs); $i<$cnt; $i++) {
                list($name, $value) = explode('=', $qs[$i]);
                $qs[$name] = $value;
                unset($qs[$i]);
            }
        }

        foreach ($qs as $name => $value) {
            $querystring[] = $name . '=' . $value;
        }

        return implode('&', $querystring);
    }     
}

?>
