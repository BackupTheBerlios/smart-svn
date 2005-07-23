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
 * ViewSetupIndex class
 *
 */
 
class ViewSetupIndex extends SmartView
{
     /**
     * Default template for this view
     * @var string $template
     */
    public $template = 'setup_index';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public $templateFolder = 'modules/setup/templates/';
     
    /**
     * Launch setup process
     *
     */
    public function perform( $data = FALSE )
    { 
        // Init setup_config array
        $this->viewVar['setup_config'] = array();
        // Init setup_error array
        $this->tplVar['setup_error']  = array();

        // launch setup
        if( isset($_POST['do_setup']) && (TRUE == $this->validate()) )
        {
            try
            { 
                $data = array('superuser_passwd' => SmartCommonUtil::stripSlashes($_REQUEST['syspassword']),
                              'dbtablesprefix'   => SmartCommonUtil::stripSlashes($_REQUEST['dbtablesprefix']),
                              'dbhost'           => SmartCommonUtil::stripSlashes($_REQUEST['dbhost']),
                              'dbuser'           => SmartCommonUtil::stripSlashes($_REQUEST['dbuser']),
                              'dbpasswd'         => SmartCommonUtil::stripSlashes($_REQUEST['dbpasswd']),
                              'dbname'           => SmartCommonUtil::stripSlashes($_REQUEST['dbname']),
                              'charset'          => SmartCommonUtil::stripSlashes($_REQUEST['charset']),
                              'config'           => & $this->viewVar['setup_config']); 
                              
                // Send a broadcast setup message to all modules  
                $this->model->broadcast( 'setup', $data );            

                // write config file with database connection settings      
                $this->model->action( $this->config['base_module'], 
                                      'setDbConfig', 
                                      array( 'dbConnect' => & $this->viewVar['setup_config']['db'],
                                             'error'     => ($error = FALSE)) );     
                
                // reload the admin interface after successfull setup
                ob_clean();
                @header('Location: ' . $this->config['admin_web_controller']);
                exit;
            }
            catch(SmartDbException $e)
            {
                // set path to the log file
                $e->flag['logs_path'] = $this->config['logs_path'];
                SmartExceptionLog::log( $e );
                $this->tplVar['setup_error'][] = $e->getMessage();

                // Rollback all module setup actions 
                $this->rollback();
            }  
            catch(SmartModelException $e)
            {
                // set path to the log file
                $e->flag['logs_path'] = $this->config['logs_path'];
                SmartExceptionLog::log( $e );
                $this->tplVar['setup_error'][] = $e->getMessage();             
                $this->rollback();
            }   
            catch(Exception $e)
            {
                // set path to the log file
                $e->flag['logs_path'] = $this->config['logs_path'];
                // log this exception
                SmartExceptionLog::log( $e );
                // set template error variables                
                $this->tplVar['setup_error'][] = $e->getMessage();
            }            
        }

        // Fill up the form field variables with posted data        
        if(isset($_REQUEST['dbtype']))
        {
          $this->tplVar['dbtype'] = SmartCommonUtil::stripSlashes($_REQUEST['dbtype']);   
        }
        if(isset($_REQUEST['charset']))
        {
          $this->tplVar['charset'] = SmartCommonUtil::stripSlashes($_REQUEST['charset']);   
        }        
        if(isset($_REQUEST['create_db']))
        {
          $this->tplVar['form_created_db'] = TRUE;   
        }      
        
        if(isset($_REQUEST['dbhost']))
        {
          $this->tplVar['form_dbhost'] = SmartCommonUtil::stripSlashes($_REQUEST['dbhost']);   
        }
        if(isset($_REQUEST['dbuser']))
        {
          $this->tplVar['form_dbuser'] = SmartCommonUtil::stripSlashes($_REQUEST['dbuser']);   
        }        
        if(isset($_REQUEST['dbname']))
        {
          $this->tplVar['form_dbname'] = SmartCommonUtil::stripSlashes($_REQUEST['dbname']);   
        }
        if(isset($_REQUEST['dbpasswd']))
        {
          $this->tplVar['form_dbpasswd'] = SmartCommonUtil::stripSlashes($_REQUEST['dbpasswd']);   
        }        
        if(isset($_REQUEST['dbtablesprefix']))
        {
          $this->tplVar['form_dbtableprefix'] = SmartCommonUtil::stripSlashes($_REQUEST['dbtablesprefix']);   
        }        
    
        if(isset($_REQUEST['syspassword']))
        {
          $this->tplVar['form_syspassword'] = SmartCommonUtil::stripSlashes($_REQUEST['syspassword']);   
        }        
      
        return TRUE;
    }   
    /**
     * Validate form data
     *
     * @return bool true on success else false
     */    
    private function validate()
    {
        if(empty($_REQUEST['dbhost']))
        {
            $this->tplVar['setup_error'][] = 'Database Host field is empty';
        }
        if(empty($_REQUEST['dbuser']))
        {
            $this->tplVar['setup_error'][] = 'Database User field is empty';
        }  
        if(empty($_REQUEST['dbname']))
        {
            $this->tplVar['setup_error'][] = 'Database Name field is empty';
        }  
        elseif(preg_match("/[^a-zA-Z_0-9]/",$_REQUEST['dbname']))
        {
            $this->tplVar['setup_error'][] = 'Only a-z A-Z _ 0-9 chars for database name are accepted';
        }    
        if(preg_match("/[^a-zA-Z_0-9]/",$_REQUEST['dbtablesprefix']))
        {
            $this->tplVar['setup_error'][] = 'Only a-z A-Z _ 0-9 chars for database name prefix are accepted';
        }         
        
        if(empty($_REQUEST['syspassword']) || empty($_REQUEST['syspassword']))
        {
            $this->tplVar['setup_error'][] = 'Both Sysadmin password fields should not be empty and must contain the same value';
        } 
        if(preg_match("/[^a-zA-Z0-9]/",$_REQUEST['syspassword']))
        {
            $this->tplVar['setup_error'][] = 'Only a-z A-Z 0-9 chars for superuser password areis accepted';
        }        
        
        if(count($this->tplVar['setup_error']) > 0)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Rollback setup of each module
     *
     */    
    private function rollback()
    {
        $data = array('superuser_passwd' => SmartCommonUtil::stripSlashes($_REQUEST['syspassword']),
                      'dbtablesprefix'   => SmartCommonUtil::stripSlashes($_REQUEST['dbtablesprefix']),
                      'dbhost'           => SmartCommonUtil::stripSlashes($_REQUEST['dbhost']),
                      'dbuser'           => SmartCommonUtil::stripSlashes($_REQUEST['dbuser']),
                      'dbpasswd'         => SmartCommonUtil::stripSlashes($_REQUEST['dbpasswd']),
                      'dbname'           => SmartCommonUtil::stripSlashes($_REQUEST['dbname']),
                      'charset'          => SmartCommonUtil::stripSlashes($_REQUEST['charset']),
                      'config'           => & $this->viewVar['setup_config'],
                      'rollback'         => TRUE);            
    
        $this->model->broadcast( 'setup', $data );    
    }
}

?>