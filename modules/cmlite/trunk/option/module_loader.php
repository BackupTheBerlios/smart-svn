<?php
// ----------------------------------------------------------------------
// Smart (PHP Framework)
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * Module loader of the option module
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Init this variable
$B->modul_options = FALSE;

// Show main options if no module feature is requested.
if(!isset($_GET['mf']))
{
   // Empty public web cache
   if(isset($_POST['clean_cache']))
   {
        include_once(SF_BASE_DIR.'/admin/lib/PEAR/Cache/Lite.php');
                
        $options = array(
            'cacheDir' => SF_BASE_DIR.'/admin/tmp/cache/'
        ); 
            
        $B->_cache = & new Cache_Lite($options);    
        $B->_cache->clean();
        unset($B->_cache);
    }
    elseif (isset($_POST['update_main_options']))
    {
        // set options of other modules
        $B->B( EVT_SET_OPTIONS );
        
        // set main options
        $B->sys['option']['url']        = $B->util->stripSlashes($_POST['site_url']);
        $B->sys['option']['email']      = $B->util->stripSlashes($_POST['site_email']);
        $B->sys['option']['site_title'] = $B->util->stripSlashes($_POST['site_title']);
        $B->sys['option']['site_desc']  = $B->util->stripSlashes($_POST['site_desc']);
        $B->sys['option']['charset']    = $B->util->stripSlashes($_POST['charset']);
        $B->sys['option']['tpl']        = $B->util->stripSlashes($_POST['tpl']);
        
        $B->conf->setConfigValues( $B->sys );
        //  write a new file
        $B->conf->writeConfigFile( 'config_system.xml.php', array('comment' => 'Main config file', 'filetype' => 'xml', 'mode' => 'pretty') );

        // insert bad word languages list
        if(!empty($_POST['bad_word_list']))
        {
            // Insert bad word list in db table
            $bad_word_file = SF_BASE_DIR.'/admin/modules/option/bad_word/stop.'.$_POST['bad_word_list'].'.sql';
            if( TRUE == @is_file($bad_word_file) )
            {
                $bad_word = @file($bad_word_file);
                $sth = $B->db->autoPrepare($B->sys['db']['table_prefix'].'bad_words', array('word','lang'),DB_AUTOQUERY_INSERT);
                $_ins = array();
                foreach($bad_word as $word)
                {
                    if(!strstr($word,"#"))
                    {
                        $_ins[] = array($word,$_POST['bad_word_list']);
                    }
                }
                $result = &$B->db->executeMultiple($sth, $_ins);
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
        if(isset($_POST['selected_lang']) && (count($_POST['selected_lang']) > 0))
        {
            include_once(SF_BASE_DIR.'/admin/include/class.sfWordIndexer.php');

            foreach($_POST['selected_lang'] as $lang)
                word_indexer::delete_bad_words_lang( $lang );              
        }        
    }    
    
    // Load the available public templates sets from the main folder 
    $B->templ = array();
    $directory =& dir(SF_BASE_DIR);

    while (false != ($dirname = $directory->read()))
    {
        if (FALSE == is_dir(SF_BASE_DIR . '/' . $dirname))
        {
            if(preg_match("/(^[^_]+).*\.tpl\.php$/", $dirname, $tmp))
            {
                if(!in_array($tmp[1], $B->templ))
                    $B->templ[] = $tmp[1];
            }
        }
    }

    $directory->close();

    include_once(SF_BASE_DIR.'/admin/include/class.sfWordIndexer.php');

    // get actif bad words languages
    $B->tpl_selected_lang = word_indexer::get_bad_words_lang();

    // Get available language bad word list
    //
    $directory =& dir(SF_BASE_DIR.'/admin/modules/option/bad_word');

    $B->tpl_bad_word_lang = array();

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
                if(FALSE == in_array($tmp[1], $B->tpl_selected_lang))
                {
                    $B->tpl_bad_word_lang[] = $tmp[1];
                }
            }
        }  
    }

    // Load options templates from other modules    
    $B->mod_option = array();
    $B->B( EVT_GET_OPTIONS );
}
else
{
    //Load options of the requested modul
    if(FALSE === ($B->modul_options = $B->M( $_GET['mf'], EVT_LOAD_OPTIONS)))
    {
        trigger_error( "This modul handler dosent exist: " . $_GET['mf'] . "\n" . __FILE__ . "\n" . __LINE__, E_USER_ERROR  );            
    }
}

// set the base template for this module
$B->module = SF_BASE_DIR . '/admin/modules/option/templates/index.tpl.php';    

?>
