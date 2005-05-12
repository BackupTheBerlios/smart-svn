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
                // Send a broadcast setup message to all modules  
                $this->model->broadcast( 'setup', 
                                         array('request' => & $_REQUEST,
                                               'config'  => & $this->viewVar['setup_config']) );            

                // write config file with database connection settings      
                $result = $this->model->action( $this->config['base_module'], 
                                                'setDbConfig', 
                                                array( 'dbConnect' => & $this->viewVar['setup_config']['db'],
                                                       'error'     => ($error = FALSE)) );     
                
                if($result != TRUE)
                {
                    throw new Exception($error);
                }
                
                // reload the admin interface
                ob_clean();
                @header('Location: ' . $this->config['admin_web_controller']);
                exit;
            }
            catch(SQLException $e)
            {
                SmartExceptionLog::log( $e );
                $this->tplVar['setup_error'][] = $e->getNativeError();

                // Rollback all module setup actions 
                $this->rollback();
            }  
            catch(SmartModelException $e)
            {
                SmartExceptionLog::log( $e );
                $this->tplVar['setup_error'][] = 'Database connection error: ' . $e->getMessage();             
            }   
            catch(Exception $e)
            {
                $e->flag['logs_path'] = $this->model->config['logs_path'];
                SmartExceptionLog::log( $e );

                $this->tplVar['setup_error'][] = $e->getMessage();
                $this->rollback();
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

        if(isset($_REQUEST['sysname']))
        {
          $this->tplVar['form_sysname'] = SmartCommonUtil::stripSlashes($_REQUEST['sysname']);   
        }    
        if(isset($_REQUEST['syslastname']))
        {
          $this->tplVar['form_syslastname'] = SmartCommonUtil::stripSlashes($_REQUEST['syslastname']);   
        } 
        if(isset($_REQUEST['syslogin']))
        {
          $this->tplVar['form_syslogin'] = SmartCommonUtil::stripSlashes($_REQUEST['syslogin']);   
        } 
        if(isset($_REQUEST['syspassword1']))
        {
          $this->tplVar['form_syspassword1'] = SmartCommonUtil::stripSlashes($_REQUEST['syspassword1']);   
        } 
        if(isset($_REQUEST['syspassword2']))
        {
          $this->tplVar['form_syspassword2'] = SmartCommonUtil::stripSlashes($_REQUEST['syspassword2']);   
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
        if(preg_match("/[^a-zA-Z_0-9]/",$_REQUEST['dbname']))
        {
            $this->tplVar['setup_error'][] = 'Only a-z A-Z _ 0-9 chars for database name are accepted';
        }    
        if(preg_match("/[^a-zA-Z_0-9]/",$_REQUEST['dbtablesprefix']))
        {
            $this->tplVar['setup_error'][] = 'Only a-z A-Z _ 0-9 chars for database name prefix are accepted';
        }         

        if(empty($_REQUEST['sysname']))
        {
            $this->tplVar['setup_error'][] = 'SysAdmin name field is empty';
        }
        if(empty($_REQUEST['syslastname']))
        {
            $this->tplVar['setup_error'][] = 'SysAdmin lastname field is empty';
        }  
        if(empty($_REQUEST['syslogin']))
        {
            $this->tplVar['setup_error'][] = 'SysAdmin login field is empty';
        }  
        if(preg_match("/[^a-zA-Z_0-9-]/",$_REQUEST['syslogin']))
        {
            $this->tplVar['setup_error'][] = 'Only a-z A-Z _ 0-9 - chars for SysAdmin login are accepted';
        } 
        if(empty($_REQUEST['syspassword1']) || empty($_REQUEST['syspassword2']))
        {
            $this->tplVar['setup_error'][] = 'Both Sysadmin password fields should not be empty and must contain the same value';
        }  
        elseif($_REQUEST['syspassword1'] !== $_REQUEST['syspassword2'])
        {
            $this->tplVar['setup_error'][] = 'Both Sysadmin password fields should not be empty and must contain the same value';
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
        $this->model->broadcast( 'setup', 
                                 array('rollback' => TRUE,
                                       'request'  => & $_REQUEST) );    
    }
}

?>