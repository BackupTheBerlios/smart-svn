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

include_once('File/Find.php');
include_once('File.php');

class action_navigation_get extends action
{
    /**
     * Fill up an array with navigation elements
     *
     * Structure of the $data array:
     * $data['var']           - array name where to store navigation array
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $f = new File_Find();
        $this->fp = new File();
        
        // get var name defined in the public template to store the result
        $_result = & $this->B->$data['var']; 
            
        $item = $f->maptree(SF_BASE_DIR . 'data/navigation');
        $this->_get_items( $item, $_result );
    }  
    
    function _get_items(&$item , &$result)
    {
        sort($item[0]);
        foreach ($item[0] as $i)
        {
            $node = $this->fp->readLine($i.'/node');
            $dir = basename($i);
            if($dir != 'navigation')
            {
                $result[$dir] = $node;
            }
        }
    }
}

?>
