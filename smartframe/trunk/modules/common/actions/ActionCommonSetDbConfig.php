<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * Write php file with db connection array data
 *
 * USAGE:
 * $model->action( $config['base_module'],'setDbConfig', // usually 'common' is base module
 *                 array( 'dbConnect' => & array) );     // array with db connect data     
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
           throw new SmartModelException("Cant open file to write: ". $this->config['config_path'] . "dbConnect.php");
        }
         
        $connect_str = $this->buildContent( $data );
        
        if( !@fwrite($fp, $connect_str) )
        {
            throw new SmartModelException("Cant write file: ". $this->config['config_path'] . "dbConnect.php");      
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
            throw new SmartModelException("Config folder isnt writeable. Check permission on: ". $this->model->config['config_path']);            
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
        $str = "<?php \n\n if (!defined( 'SMART_SECURE_INCLUDE' )) \n  exit;\n\n";
        
        $str .= '$db'." = array();\n\n";
        
        $str .= '$db["dbhost"]'       ." = '{$data['dbConnect']['dbhost']}';\n";
        $str .= '$db["dbport"]'       ." = '{$data['dbConnect']['dbport']}';\n";
        $str .= '$db["dbcharset"]'    ." = '{$data['dbConnect']['dbcharset']}';\n";
        $str .= '$db["dbuser"]'       ." = '{$data['dbConnect']['dbuser']}';\n";
        $str .= '$db["dbpasswd"]'     ." = '{$data['dbConnect']['dbpasswd']}';\n";
        $str .= '$db["dbname"]'       ." = '{$data['dbConnect']['dbname']}';\n";
        $str .= '$db["dbTablePrefix"]'." = '{$data['dbConnect']['dbTablePrefix']}';\n\n";
        
        $str .= "?>";
        
        return $str;
    }
}

?>