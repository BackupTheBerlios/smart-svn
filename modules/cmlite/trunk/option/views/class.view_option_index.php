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
 * view_option_index class 
 *
 */

class view_option_index extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'option_index';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/option/templates/';
    
    /**
     * Evaluate the option requests of the option template tpl.option_index.php
     *
     * @param array $data
     */
    function perform(  $data = FALSE  )
    {    
        // Init this variable
        $this->B->modul_options = FALSE;

        // update the main options on demande
        $this->_update_main_options();
        
        // update options of other modules
        B( 'set_options' );
        
        // if some config are modified, write the config file and reload the page
        if($this->B->_modified == TRUE)
        {
            M( MOD_COMMON, 'sys_update_config', $this->B->sys );    
            @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1&m=option');
            exit;
        }

        // update language bad words list
        $this->_update_bad_words_list();
        
        // assign tpl array with available public template groups
        $this->_load_public_tpl_groups();

        // assign tpl array with available public template groups
        $this->_load_public_view_groups();

        // assign tpl array with available bad word lists
        $this->_get_bad_words_list();

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
        
        // Empty all cache data
        if(isset($_POST['update_clean_cache']))
        {
            // Delete cache data
            M( MOD_COMMON, 'cache_delete', array('group' => ''));
        }       
        elseif (isset($_POST['update_main_options_email']))
        {
            $this->B->sys['option']['email'] = $_POST['site_email'];
            $this->B->_modified = TRUE;
        } 
        elseif (isset($_POST['update_main_options_title']))
        {
            $this->B->sys['option']['site_title'] = htmlspecialchars(commonUtil::stripSlashes($_POST['site_title']), ENT_QUOTES);
            $this->B->sys['option']['site_desc']  = htmlspecialchars(commonUtil::stripSlashes($_POST['site_desc']), ENT_QUOTES);
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
        elseif (isset($_POST['update_main_options_view']))
        {
            $this->B->sys['option']['view'] = $_POST['viewgroup'];
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
         M( MOD_COMMON,
            'bad_words',
            array('get_bad_words_lang' => TRUE,
                  'var'                => 'tpl_selected_lang'));
    
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
            {
                M( MOD_COMMON,
                   'bad_words',
                   array( 'delete_bad_words_lang' => $lang));            
            }              
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
         $this->B->templatefolder = array();
         
         // the root directory
         $this->B->templatefolder[] = "";
         
         $directory =& dir(SF_BASE_DIR);

         while (false != ($itemname = $directory->read()))
         {
             if (TRUE == is_dir(SF_BASE_DIR . '/' . $itemname))
             {
                if(preg_match("/^templates_/", $itemname))
                {
                    $this->B->templatefolder[] = $itemname . "/";
                }
             }
         }

         $directory->close();    
    }
    /**
     * Assign view array with all available public view folders
     *
     * @access privat
     */     
    function _load_public_view_groups()
    {
         // Load the available public view folders
         $this->B->viewfolder = array();
         
         $directory =& dir(SF_BASE_DIR);

         while (false != ($itemname = $directory->read()))
         {
             if (TRUE == is_dir(SF_BASE_DIR . '/' . $itemname))
             {
                if(preg_match("/^views_/", $itemname))
                {
                    $this->B->viewfolder[] = $itemname . "/";
                }
             }
         }

         $directory->close();    
    }    
}

?>
