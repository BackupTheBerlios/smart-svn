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
 * OPTION_SYS_LOAD_MODULE class 
 *
 */
 
class OPTION_SYS_LOAD_MODULE
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
    function OPTION_SYS_LOAD_MODULE()
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
     * Perform on admin requests for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // Init this variable
        $this->B->modul_options = FALSE;

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
            $this->B->sys['option']['tpl'] = $_POST['tpl'];
            $this->B->_modified = TRUE;
        }     
    
        // set options of other modules
        $this->B->B( 'SET_OPTIONS' );
 
        // if some config are modified, write the config file and reload the page
        if($this->B->_modified == TRUE)
        {
            // include PEAR Config class
            include_once( SF_BASE_DIR . '/admin/modules/common/PEAR/Config.php');

            $c = new Config();
            $root =& $c->parseConfig($this->B->sys, 'PHPArray');
            $c->writeConfig(SF_BASE_DIR . '/admin/modules/common/config/config.php', 'PHPArray', array('name' => 'B->sys'));
    
            @header('Location: '.SF_BASE_LOCATION.'/admin/index.php?m=OPTION');
            exit;
        }

        // insert bad word languages list
        if(isset($_POST['update_main_options_badwordadd']) && !empty($_POST['bad_word_list']))
        {
            // Insert bad word list in db table
            $bad_word_file = SF_BASE_DIR.'/admin/modules/option/bad_word/stop.'.$_POST['bad_word_list'].'.sql';
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
            include_once(SF_BASE_DIR.'/admin/modules/common/class.sfWordIndexer.php');
            foreach($_POST['selected_lang'] as $lang)
                word_indexer::delete_bad_words_lang( $lang );              
         }           
    
         // Load the available public templates sets from the main folder 
         $this->B->templ = array();
         $directory =& dir(SF_BASE_DIR);

         while (false != ($dirname = $directory->read()))
         {
             if (FALSE == is_dir(SF_BASE_DIR . '/' . $dirname))
             {
                 if(preg_match("/(^[^_]+).*\.tpl\.php$/", $dirname, $tmp))
                 {
                     if(!in_array($tmp[1], $this->B->templ))
                         $this->B->templ[] = $tmp[1];
                 }
             }
         }

         $directory->close();
 
         include_once(SF_BASE_DIR.'/admin/modules/common/class.sfWordIndexer.php');
    
         // get actif bad words languages
         $this->B->tpl_selected_lang = word_indexer::get_bad_words_lang();
    
         // Get available language bad word list
         //
         $directory =& dir(SF_BASE_DIR.'/admin/modules/option/bad_word');

         $this->B->tpl_bad_word_lang = array();

         while (false != ($filename = $directory->read()))
         {
             if ( ( $filename == "." ) || ( $filename == ".." ) )
             {
                 continue;
             }            
             // Test filename
             //
             if(TRUE == @is_file(SF_BASE_DIR.'/admin/modules/option/bad_word/'.$filename))
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

         // Load options templates from other modules    
         $this->B->mod_option = array();
         $this->B->B( 'GET_OPTIONS' );

         // set the base template for this module
         $this->B->module = SF_BASE_DIR . '/admin/modules/option/templates/index.tpl.php';       
    } 
}

?>