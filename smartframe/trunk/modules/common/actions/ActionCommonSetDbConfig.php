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
 * Write php file with db connection array data
 *
 */

class ActionCommonSetDbConfig extends SmartAction
{
    /**
     * Write php file with db connection array data
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        if(!$fp = @fopen(SMART_CONFIG_PATH . 'dbConnect.php', 'w'))
        {
           throw new Exception("Cant open file to write: ". SMART_CONFIG_PATH . "dbConnect.php", 2 );
        }
         
        $connect_str = $this->buildContent( $data );
        
        if( !@fwrite($fp, $connect_str) )
        {
            throw new Exception("Cant write file: ". SMART_CONFIG_PATH . "dbConnect.php", 2 );
        }
        
        @fclose($fp);
        
        return TRUE;
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if( !@is_writeable(SMART_CONFIG_PATH) )
        {
            throw new Exception("Config folder isnt writeable. Check permission on: ". SMART_CONFIG_PATH, 2 );
        }

        return TRUE;
    }    
    
    /**
     * Build the php file content with db connect data
     *
     * @param array $data
     */    
    private function buildContent( & $data )
    {
        $str = '<?php if (!defined( "SMART_SECURE_INCLUDE" )) die("no permission on dbConnect.php"); ';
        
        $str .= '$db = array();';
        
        $str .= '$db["dbhost"] = "'.$data['dbConnect']['dbhost'].'"; ';
        $str .= '$db["dbuser"] = "'.$data['dbConnect']['dbuser'].'"; ';
        $str .= '$db["dbpasswd"] = "'.$data['dbConnect']['dbpasswd'].'"; ';
        $str .= '$db["dbname"] = "'.$data['dbConnect']['dbname'].'"; ';
        $str .= '$db["dbTablePrefix"] = "'.$data['dbConnect']['dbTablePrefix'].'"; ';
        
        $str .= '?>';
        
        return $str;
    }
}

?>