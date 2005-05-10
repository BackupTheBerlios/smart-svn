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
        if(!$fp = @fopen($this->model->config['config_path'] . 'dbConnect.php', 'w'))
        {
           $data['error'] = "Cant open file to write: ". $this->model->config['config_path'] . "dbConnect.php";
           return FALSE;
        }
         
        $connect_str = $this->buildContent( $data );
        
        if( !@fwrite($fp, $connect_str) )
        {
           $data['error'] = "Cant write file: ". $this->model->config['config_path'] . "dbConnect.php";
           return FALSE;        
        }
        
        @fclose($fp);
        
        return TRUE;
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if( !@is_writeable($this->model->config['config_path']) )
        {
           $data['error'] = "Config folder isnt writeable. Check permission on: ". $this->model->config['config_path'];
           return FALSE;             
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