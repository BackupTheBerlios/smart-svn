    function moduleExists( $moduleType, $moduleName )
    {
        if ( is_array($this->_moduleDirs[$moduleType]) )
        {
            foreach($this->_moduleDirs[$moduleType] as $_path)
            {
                if( file_exists( $_path . '/' . $moduleName . '.php') ) 
                {
                    return true;
                }
            }
        }
        
        $moduleFile =   sprintf( "%s/%s/%s.php", $this->getIncludePath(), $moduleType, str_replace( '_', '/', $moduleName ) );
        if( !file_exists( $moduleFile ) )
            return false;
        if( !is_readable( $moduleFile ) )
            return false;
        return true;
    }