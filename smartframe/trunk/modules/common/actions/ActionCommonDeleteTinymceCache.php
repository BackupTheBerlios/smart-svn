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
 * ActionCommonDeleteTinymceCache class 
 *
 */
 
class ActionCommonDeleteTinymceCache extends SmartAction
{
   /**
     * delete all cached files
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    {
        $cache_dir = SMART_BASE_DIR . 'data/common/tinymce_cache' ;
          
        if ( (($handle = @opendir( $cache_dir ))) != FALSE )
        {
            while ( (( $_file = readdir( $handle ) )) != false )
            {
                if ( ( $_file == "." ) || ( $_file == ".." ) || ($_file == '.htaccess') )
                {
                    continue;
                }
                
                $cache_file = $cache_dir .'/'. $_file;
                if(is_file($cache_file))
                {
                    if(!@unlink ($cache_file))
                    {
                        trigger_error( "Can not delete cache file: ".$cache_file, E_USER_WARNING  );
                    }
                }
            }
            @closedir( $handle );
        }
        else
        {
            trigger_error( "Can not open folder to read: ".$cache_dir, E_USER_WARNING  );
        }
        return TRUE;
    } 
}

?>
