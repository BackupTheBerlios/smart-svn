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
 * option_view_index class 
 *
 */

include_once(SF_BASE_DIR.'modules/common/class.sfWordIndexer.php');

class option_view_index
{
    /**
     * Global system instance
     * @var object $this->B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function option_view_index()
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
     * Evaluate the option requests of the option template
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // Init this variable
        $this->B->modul_options = FALSE;

        // update the main options on demande
        $this->_update_main_options();
        
        // update options of other modules
        $this->B->B( 'set_options' );
        
        // if some config are modified, write the config file and reload the page
        if($this->B->_modified == TRUE)
        {
            $this->B->M( MOD_COMMON, 'sys_update_config' );    
            @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1&m=option');
            exit;
        }

        // update language bad words list
        $this->_update_bad_words_list();
        
        // assign tpl array with available public template groups
        $this->_load_public_tpl_groups();

        // assign tpl array with available bad word lists
        $this->_get_bad_words_list();

        // Load options templates from other modules    
        $this->B->mod_option = array();
        $this->B->B( 'get_options' );

        return TRUE;     
    } 

    /**
     * Update main options
     *
     * @access privat
     */    
    function _update_main_options()
    {
        // init var - used if a config value has been modified
        $this->B->_modified = FALSE;
        
        // Empty public web cache
        if(isset($_POST['cleancache']))
        {
            include_once(SF_BASE_DIR.'/admin/modules/user/PEAR/Cache/Lite.php');
                 
            $options = array( 'cacheDir' => SF_BASE_DIR.'/admin/tmp/cache/' ); 
             
            $this->B->_cache = & new Cache_Lite($options);    
            $this->B->_cache->clean();
            unset($this->B->_cache);
        }
        elseif (isset($_POST['update_main_options_url']))
        {
            $this->B->sys['option']['url'] = commonUtil::addSlashes($_POST['site_url']);
            $this->B->_modified = TRUE;
        }
        elseif (isset($_POST['update_main_options_email']))
        {
            $this->B->sys['option']['email'] = commonUtil::addSlashes($_POST['site_email']);
            $this->B->_modified = TRUE;
        } 
        elseif (isset($_POST['update_main_options_title']))
        {
            $this->B->sys['option']['site_title'] = commonUtil::addSlashes($_POST['site_title']);
            $this->B->sys['option']['site_desc']  = commonUtil::addSlashes($_POST['site_desc']);
            $this->B->_modified = TRUE;
        } 
        elseif (isset($_POST['update_main_options_charset']))
        {
            $this->B->sys['option']['charset'] = $_POST['charset'];
            $this->B->_modified = TRUE;
        }  
        elseif (isset($_POST['update_main_options_tpl']))
        {
            $this->B->sys['option']['tpl'] = $_POST['tplgroup'];
            $this->B->_modified = TRUE;
        }   
    }

    /**
     * Assign tpl array with all available bad word lists
     *
     * @access privat
     */ 
    function _get_bad_words_list()
    {
         // get actif bad words languages
         $this->B->tpl_selected_lang = word_indexer::get_bad_words_lang();
    
         // Get available language bad word list
         //
         $directory =& dir(SF_BASE_DIR.'modules/option/bad_word');

         $this->B->tpl_bad_word_lang = array();

         while (false != ($filename = $directory->read()))
         {
             if ( ( $filename == "." ) || ( $filename == ".." ) )
             {
                 continue;
             }            
             // Test filename
             //
             if(TRUE == @is_file(SF_BASE_DIR.'modules/option/bad_word/'.$filename))
             {
                 // Extract language from file name
                 if(preg_match("/^stop\.([^\.]+)/", $filename, $tmp))
                 {
                     // Check if language is installed
                     if(FALSE == in_array($tmp[1], $this->B->tpl_selected_lang))
                     {
                         $this->B->tpl_bad_word_lang[] = $tmp[1];
                     }
                 }
             }  
         }    
    }

    /**
     * Add language bad words in db table
     *
     * @access privat
     */     
    function _update_bad_words_list()
    {
        // insert bad word languages list
        if(isset($_POST['update_main_options_badwordadd']) && !empty($_POST['bad_word_list']))
        {
            // Insert bad word list in db table
            $bad_word_file = SF_BASE_DIR.'modules/option/bad_word/stop.'.$_POST['bad_word_list'].'.sql';
            if( TRUE == @is_file($bad_word_file) )
            {
                $bad_word = @file($bad_word_file);
                $sth = $this->B->db->autoPrepare($this->B->sys['db']['table_prefix'].'bad_words', array('word','lang'),DB_AUTOQUERY_INSERT);
                $_ins = array();
                foreach($bad_word as $word)
                {
                    if(!strstr($word,"#"))
                    {
                        $_ins[] = array($word,$_POST['bad_word_list']);
                    }
                }
                $result = &$this->B->db->executeMultiple($sth, $_ins);
                if (DB::isError($result)) 
                {
                    trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                }                 
            }
            else
            {
                trigger_error("No ".$bad_word_file." file for this module!\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            }  
         }
         // delete selected bad word languages
         elseif(isset($_POST['update_main_options_badworddel']) && isset($_POST['selected_lang']) && (count($_POST['selected_lang']) > 0))
         {
            foreach($_POST['selected_lang'] as $lang)
                word_indexer::delete_bad_words_lang( $lang );              
         }        
    }

    /**
     * Assign template array with all available public template groups
     *
     * @access privat
     */     
    function _load_public_tpl_groups()
    {
         // Load the available public templates sets from the main folder 
         $this->B->templ = array();
         $directory =& dir(SF_BASE_DIR);

         while (false != ($itemname = $directory->read()))
         {
             if (FALSE == is_dir(SF_BASE_DIR . '/' . $itemname))
             {
                if(preg_match("/(^[^_]+).*\.tpl\.php$/", $itemname, $tmp))
                {
                    if(($tmp[1] != 'error') && !in_array($tmp[1], $this->B->templ))
                        $this->B->templ[] = $tmp[1];
                }
             }
         }

         $directory->close();    
    }
}

?>
