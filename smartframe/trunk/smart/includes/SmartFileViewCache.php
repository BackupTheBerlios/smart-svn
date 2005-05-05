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
 * Smart file cache class
 *
 *
 */
 
class SmartFileViewCache extends SmartCache
{
    /**
     * Cache file (full path)
     *
     * @var string $cacheFile
     */
    private $cacheFile;
    /**
     * Cache Time file (full path)
     *
     * @var string $cacheTimeFile
     */
    private $cacheTimeFile;
    /**
     * Temporary Cache file (full path)
     *
     * @var string $cacheFileTmp
     */
    private $cacheFileTmp;
    /**
     * Temporary Cache Time file (full path)
     *
     * @var string $cacheTimeFileTmp
     */
    private $cacheTimeFileTmp;    
    /**
     * File pointer
     *
     * @var resource $cachefp
     */
    private $cachefp;    
    /**
     * Time File pointer
     *
     * @var resource $cacheTimefp
     */
    private $cacheTimefp; 
    
    /**
     * Check if cache id exists
     *
     * @var int $expire expire time stamp
     */   
    public function cacheIdExists( $expire, $viewId )
    {
        $this->cacheFile = SMART_CACHE_PATH.md5($_SERVER['PHP_SELF'].$viewId);
        $this->cacheTimeFile = $this->cacheFile . 'time';

        if( file_exists($this->cacheTimeFile)  )
        {
            $cachetime = file_get_contents($this->cacheTimeFile);

            if( ($cachetime != FALSE) && ((time() - $expire) < intval($cachetime))  )
            {
                include($this->cacheFile);
                return TRUE;
            } 
        }    
                
        $this->cacheTimeFileTmp = $this->cacheTimeFile.'.'.getmypid();
        $this->cacheTimefp = fopen($this->cacheTimeFileTmp, 'w');        
        $this->cacheFileTmp = $this->cacheFile.'.'.getmypid();
        $this->cachefp = fopen($this->cacheFileTmp, 'w');
        
        return FALSE;
    }

    /**
     * Write content into a cache file
     *
     * @var string $content
     */     
    public function cacheWrite( & $content )
    {
        if($this->cacheTimefp)
        {
            $os = strtoupper(substr(PHP_OS, 0, 3));
            
            fwrite($this->cacheTimefp, strval(time()));
            fclose($this->cacheTimefp);
            if( ($os === 'WIN') && file_exists($this->cacheTimeFile)  )
            {
                unlink($this->cacheTimeFile);
            }
            @rename($this->cacheTimeFileTmp, $this->cacheTimeFile);
            
            if($this->cachefp)
            {
                fwrite($this->cachefp, $content);
                fclose($this->cachefp);
                if( ($os === 'WIN') && file_exists($this->cacheFile)  )
                {
                    unlink($this->cacheFile);
                }                
                @rename($this->cacheFileTmp, $this->cacheFile);
            }
        }    
    }    
}

?>