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
 * action_navigation_get class 
 *
 */
 
// tree class
include_once(SF_BASE_DIR . 'modules/common/includes/Tree.php');

class action_navigation_get_node extends action
{
    /**
     * Fill up variables with navigation node title and text
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {        
        // check if a tree object exists
        if(!is_object($this->B->tree))
        {
            // load navigation nodes
            $file = SF_BASE_DIR . 'data/navigation/nodes.php';     
            
            $this->B->tree = & new Tree($file);
        }  

        $_result = & $this->B->$data['result'];      

        // check if cache ID exists
        if ( M( MOD_COMMON, 
                'cache_get',
                array('result'     => $data['result'],
                      'cacheID'    => SF_SECTION.$data['node'],
                      'cacheGroup' => 'navigation'))) 
        {
            return TRUE;
        }  

        $_result = $this->B->tree->getAllData( $data['node'] );     

        // format text
        if( $data['format'] == 'wikki' )
        {
            if(!is_object($this->B->wiki))
            {
                include_once(SF_BASE_DIR . 'modules/common/PEAR/Text/Wiki.php');
                $this->B->wiki = & new Text_Wiki();
            }
            $_result['body'] = $this->B->wiki->transform($_result['body'], 'Xhtml');    
        }

        // save result to cache
        M( MOD_COMMON, 
           'cache_save',
           array('result' => $_result));  

    }   
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    function validate(  $data = FALSE  )
    {
        // validate $data['node']. no chars else than 0123456789 are accepted
        if( preg_match("/[^0-9-]/", $data['node']) )
        {
            $this->B->$data['error']  = 'Wrong node format';
            return FALSE;
        }   
        
        return TRUE;
    }        
}

?>