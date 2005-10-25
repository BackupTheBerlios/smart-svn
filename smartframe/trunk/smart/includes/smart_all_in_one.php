<?php if (!defined( 'SMART_SECURE_INCLUDE' )) { die('no permission on smart_core.php'); } @ob_end_clean(); ob_start(); $SmartConfig = array(); $SmartConfig['admin_web_controller'] = 'admin.php'; $SmartConfig['public_web_controller'] = 'index.php'; $SmartConfig['config_path'] = SMART_BASE_DIR . 'config/'; $SmartConfig['logs_path'] = SMART_BASE_DIR . 'logs/'; $SmartConfig['cache_path'] = SMART_BASE_DIR . 'cache/'; $SmartConfig['base_module'] = 'common'; $SmartConfig['default_module'] = 'default'; $SmartConfig['setup_module'] = 'setup'; $SmartConfig['cache_type'] = 'SmartFileViewCache'; $SmartConfig['cache_time_type'] = 'filemtime'; $SmartConfig['public_template_engine'] = 'SmartTplContainerPhp'; $SmartConfig['template_engine'] = 'SmartTplContainerPhp'; $SmartConfig['useCodeAnalyzer'] = FALSE; $SmartConfig['allowedConstructs'] = array('if','else','elseif','else if','endif', 'foreach','endforeach','while','do','for','endfor', 'continue','break','switch','case', 'echo','print','print_r','var_dump','exit', 'defined','define', 'isset','empty','count'); $SmartConfig['admin_view_folder'] = 'views/'; $SmartConfig['default_template_folder'] = 'templates_smart/'; $SmartConfig['default_view_folder'] = 'views_smart/'; $SmartConfig['default_view'] = 'index'; $SmartConfig['error_view'] = 'error'; $SmartConfig['output_compression'] = FALSE; $SmartConfig['output_compression_level'] = '4'; $SmartConfig['system_email'] = ''; $SmartConfig['message_handle'] = 'LOG'; $SmartConfig['error_reporting'] = E_ALL; $SmartConfig['debug'] = FALSE; $SmartConfig['media_folder_rights'] = 0777; $SmartConfig['media_file_rights'] = 0777; if (@file_exists(SMART_BASE_DIR . 'config/my_config.php')) { include_once(SMART_BASE_DIR . 'config/my_config.php'); } $SmartConfig['view_map'] = array(); $SmartConfig['smart_version'] = '0.2.1a'; $SmartConfig['smart_version_name'] = 'SMART3'; $SmartConfig['disable_cache'] = 0; ini_set( 'include_path', '.' . PATH_SEPARATOR . SMART_BASE_DIR . 'smart/includes/PEAR' . PATH_SEPARATOR . ini_get('include_path') ); abstract class SmartObject { public function __toString () { $vars = get_object_vars($this); $return_str = ''; foreach($vars as $key => $val) { $return_str .= $key . " = " . $val . "\n"; } return $return_str; } } class SmartException extends Exception { public $flag = array(); public function __construct ($message = null, $code = 0) { parent::__construct($message, $code); $this->setName('SmartException'); } public function getName () { return $this->name; } protected function setName ($name) { $this->name = $name; } public function performStackTrace() { $this->exceptionMessage = "EXCEPTION: ".date("Y-m-d H:i:s", time())."\n"; $this->exceptionMessage .= "NAME: " .$this->getName()."\n"; $this->exceptionMessage .= "MESSAGE: " .$this->getMessage()."\n"; $this->exceptionMessage .= "CODE: " .$this->getCode()."\n"; $this->exceptionMessage .= "FILE: " .$this->getFile()."\n"; $this->exceptionMessage .= "LINE: " .$this->getLine()."\n"; $this->exceptionMessage .= "TRACE: \n" .var_export($this->getTrace(), TRUE)."\n"; $this->_log(); } function _log() { if(strstr($this->flag['message_handle'], 'LOG')) { error_log($this->exceptionMessage."\n\n", 3, $this->flag['logs_path'] . 'error.log'); } if(strstr($this->flag['message_handle'], 'SHOW') && ($this->flag['debug'] == TRUE )) { if(preg_match("/web|admin/", $this->flag['controller_type'])) { echo '<pre style="font-family: Verdana, Arial, Helvetica, sans-serif;                                  font-size: 10px;                                  color: #990000;                                  background-color: #CCCCCC;                                  padding: 5px;                                  border: thin solid #666666;">'.$this->exceptionMessage.'</pre><br />'; } elseif(preg_match("/cli/", $this->flag['controller_type'])) { fwrite(STDERR, $this->exceptionMessage, strlen($this->exceptionMessage)); } elseif(preg_match("/xml_rpc/", $this->flag['controller_type'])) { return new XML_RPC_Response(0, $GLOBALS['XML_RPC_erruser']+1, $this->exceptionMessage); } } if(strstr($this->flag['message_handle'], 'MAIL') && !empty($this->flag['system_email'])) { $header = "From: Smart3 System <{$this->flag['system_email']}>\r\n"; $header .= "MIME-Version: 1.0\r\n"; $header .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n"; $header .= "Content-Transfer-Encoding: 8bit"; mail($this->flag['system_email'], "Smart3 System Message", $this->exceptionMessage, $header); } } } class SmartExceptionLog { public static function log( $e ) { $message = "EXCEPTION: ".date("Y-m-d H:i:s", time())."\n"; $message .= "MESSAGE: " .$e->getMessage()."\n"; $message .= "CODE: " .$e->getCode()."\n"; $message .= "FILE: " .$e->getFile()."\n"; $message .= "LINE: " .$e->getLine()."\n"; $message .= "TRACE: \n" .var_export($e->getTrace(), TRUE)."\n"; @error_log($message."\n\n", 3, $e->flag['logs_path'] . 'error.log'); } } class SmartTplException extends SmartException { public function __construct ($message = null, $code = 0) { parent::__construct($message, $code); $this->setName( 'SmartTplException' ); } } class SmartViewException extends SmartException { public function __construct ($message = null, $code = 0) { parent::__construct($message, $code); $this->setName( 'SmartViewException' ); } } class SmartModelException extends SmartException { public function __construct ($message = null, $code = 0) { parent::__construct($message, $code); $this->setName( 'SmartModelException' ); } } class SmartInitException extends SmartException { public function __construct ($message = null, $code = 0) { parent::__construct($message, $code); $this->setName('SmartInitException'); } } class SmartContainerException extends SmartException { public function __construct ($message = null, $code = 0) { parent::__construct($message, $code); $this->setName( 'SmartContainerException' ); } } class SmartCacheException extends SmartException { public function __construct ($message = null, $code = 0) { parent::__construct($message, $code); $this->setName( 'SmartCacheException' ); } } class SmartDbException extends SmartException { public function __construct ($message = null, $code = 0 ) { parent::__construct($message, $code); $this->setName( 'SmartDbException' ); } } class SmartForwardAdminViewException extends Exception { public $view; public $data; public $constructorData; public $broadcast; public function __construct ($module, $view = 'index', $data = FALSE, $constructorData = FALSE, $broadcast = FALSE) { parent::__construct(NULL,0); $this->broadcast = $broadcast; $this->view = ucfirst($module).ucfirst($view); $this->data = & $data; $this->constructorData = & $constructorData; ob_clean(); } } class SmartForwardPublicViewException extends Exception { public $view; public $data; public $constructorData; public function __construct ($view = 'index', $data = FALSE, $constructorData = FALSE) { parent::__construct(NULL,0); $this->view = ucfirst($view); $this->data = & $data; $this->constructorData = & $constructorData; ob_clean(); } } class SmartContainer extends SmartObject { private static $instance = array(); private $config; function __construct( & $config) { $this->config = & $config; } public function __set( $name, $value) { if( ($this->config['debug'] == TRUE) && isset( $this->$name) ) { throw new SmartContainerException( "Value {$name} was previously declared"); } $this->$name = $value; } public function __get( $name ) { if( !isset( $this->$name) ) { return NULL; } else { return $this->$name; } } public static function newInstance($class, & $config) { if (!isset(self::$instance[$class])) { try { $class_file = SMART_BASE_DIR . 'smart/includes/'.$class.'.php'; if(!@file_exists($class_file)) { throw new SmartContainerException($class_file.' dosent exists'); } include_once($class_file); $object = new $class( $config ); if (!($object instanceof SmartContainer)) { throw new SmartContainerException($class.' dosent extends SmartContainer'); } return self::$instance[$class] = $object; } catch (SmartContainerException $e) { $e->performStackTrace(); } } return self::$instance[$class]; } } class SmartErrorHandler { private $config; function __construct( & $config ) { set_error_handler (array( &$this, '_php_error_handler' ), $config['error_reporting']); $this->config = & $config; } function _php_error_handler( $errno, $errstr, $errfile, $errline ) { $errtype = array ( E_ERROR => "E_ERROR", E_WARNING => "E_WARNING", E_PARSE => "E_PARSE", E_NOTICE => "E_NOTICE", E_CORE_ERROR => "E_CORE_ERROR", E_CORE_WARNING => "E_CORE_WARNING", E_COMPILE_ERROR => "E_COMPILE_ERROR", E_COMPILE_WARNING => "E_COMPILE_WARNING", E_USER_ERROR => "E_USER_ERROR", E_USER_WARNING => "E_USER_WARNING", E_USER_NOTICE => "E_USER_NOTICE", E_STRICT => "E_STRICT" ); $message = "\nPHP_ERROR: " . date("Y-m-d H:i:s", time()) . "\n"; $message .= "\nPHP_ERRNO: " . $errno . "\n"; $message .= "PHP_ERROR_TYPE: " . $errtype[$errno] . "\n"; $message .= "FILE: " . $errfile . "\n"; $message .= "LINE: " . $errline . "\n"; $message .= "MESSAGE: " . $errstr . "\n"; $this->_log( $message ); } function _log( & $message ) { if(strstr($this->config['message_handle'], 'LOG')) { error_log($message."\n\n", 3, $this->config['logs_path'] . 'error.log'); } if(strstr($this->config['message_handle'], 'SHOW')) { if(preg_match("/web|admin/", $this->config['controller_type'])) { echo '<pre style="font-family: Verdana, Arial, Helvetica, sans-serif;                              font-size: 10px;                              color: #990000;                              background-color: #CCCCCC;                              padding: 5px;                              border: thin solid #666666;">'.$message.'</pre><br />'; } elseif(preg_match("/cli/", $this->config['controller_type'])) { fwrite(STDERR, $message, strlen($message)); } elseif(preg_match("/xml_rpc/", $this->config['controller_type'])) { return new xmlrpcresp(0, $GLOBALS['xmlrpcerruser'], $message); } } if(strstr($this->config['message_handle'], 'MAIL') && !empty($this->config['system_email'])) { $header = "From: Smart3 System <{$this->config['system_email']}>\r\n"; $header .= "MIME-Version: 1.0\r\n"; $header .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n"; $header .= "Content-Transfer-Encoding: 8bit"; @mail($this->config['system_email'], "Smart3 System Message", $message, $header); } } } class SmartAction extends SmartObject { public $constructorData; public $model; public $config; public function __construct( $data = FALSE ) { $this->constructorData = & $data; } public function validate( $data = FALSE ) { return TRUE; } public function perform( $data = FALSE ) { return TRUE; } } class SmartModel extends SmartObject { private $registeredModules = array(); private $availaibleModules = array(); public $db; public $config; public $session; public $sessionHandler; public function __construct( & $config ) { $this->config = & $config; } public function & getModuleInfo( $module = FALSE ) { if(FALSE == $module) { return $this->registeredModules; } else { return $this->registeredModules[$module]; } } public function & getAvailaibleModules() { return $this->availaibleModules; } public function register( $module, $data ) { if(!isset($this->registeredModules[$module])) { $this->registeredModules[$module] = $data; return TRUE; } throw new SmartModelException("Duplicate error of module name: '{$module}'"); } public function init( $module ) { $this->availaibleModules[] = $module; } public function isModule( $module ) { if( !isset($this->registeredModules[$module]) ) { return FALSE; } return TRUE; } public function isVisible( $module ) { if( TRUE == $this->is_module($module) ) { if( TRUE == $this->is_active($module) ) { if( $this->registeredModules[$module]['visibility'] == TRUE ) { return TRUE; } } } return FALSE; } public function addConfigVar( $module, & $data ) { if( !isset($this->config[$module]) ) { $this->config[$module] = & $data; return TRUE; } throw new SmartInitException('Module config array exists: '.$module); } public function getConfigVar( $module = FALSE, $var_name = FALSE ) { if( isset($this->config[$module][$var_name]) ) { return $this->config[$module][$var_name]; } return NULL; } public function action( $module, $action, $data = FALSE, $constructor_data = FALSE, $force_instance = FALSE ) { $class_name = 'Action'.ucfirst($module).ucfirst($action); if( !isset($this->$class_name) || ($force_instance == TRUE) ) { $class_file = SMART_BASE_DIR . 'modules/'.$module.'/actions/'.$class_name.'.php'; if(@file_exists($class_file)) { include_once($class_file); if( $force_instance == TRUE ) { $i = 1; $new_instance = $class_name . $i; while( isset($this->$new_instance) ) { $i++; $new_instance = $class_name . $i; } $this->$new_instance = new $class_name( $constructor_data ); $class_name = & $new_instance; } else { $this->$class_name = new $class_name( $constructor_data ); } } else { return NULL; } } $this->$class_name->model = &$this; $this->$class_name->config = & $this->config; if( FALSE == $this->$class_name->validate( $data ) ) { return FALSE; } return $this->$class_name->perform( $data ); } public function broadcast( $action, $data = FALSE, $constructor_data = FALSE, $force_instance = FALSE ) { $_modules = $this->getAvailaibleModules(); foreach($_modules as $module) { $this->action( $module, $action, $data, $constructor_data, $force_instance ); } } } class SmartController extends SmartObject { private static $instance = null; public $model; public $config; private static $smartConfig; public function __construct() { try { $this->config = & self::$smartConfig; ini_set('display_errors', FALSE); error_reporting( $this->config['error_reporting'] ); $SmartContainer = new SmartContainer( $this->config ); $this->model = new SmartModel( $this->config ); new SmartErrorHandler( $this->config ); if(!is_dir(SMART_BASE_DIR . 'modules')) { throw new SmartInitException("Missing '".SMART_BASE_DIR . "modules' directory."); } $mod_common = SMART_BASE_DIR . 'modules/' . $this->config['base_module']; if(file_exists( $mod_common )) { $this->model->init( $this->config['base_module'] ); } else { throw new SmartInitException("The module '{$mod_common}'  must be installed!"); } if( isset($this->config['last_module']) ) { $last_module = $this->config['last_module']; } else { $last_module = FALSE; } $tmp_directory = dir( SMART_BASE_DIR . 'modules'); while (FALSE != ($tmp_dirname = $tmp_directory->read())) { if ( ( $tmp_dirname == '.' ) || ( $tmp_dirname == '..' ) || ( $tmp_dirname == '.svn' ) ) { continue; } if( $tmp_dirname == $last_module ) { continue; } if ( ($tmp_dirname != $this->config['base_module']) && @is_dir( SMART_BASE_DIR . 'modules/'.$tmp_dirname) ) { $this->model->init( $tmp_dirname ); } } $tmp_directory->close(); if( $last_module != FALSE ) { $mod_init = SMART_BASE_DIR . 'modules/' . $last_module; if ( @is_dir( $mod_init ) ) { $this->model->init( $last_module ); } else { throw new SmartInitException("The 'last' module folder '{$mod_init}' is missing!"); } } } catch(SmartInitException $e) { $e->performStackTrace(); } } protected function setExceptionFlags( $e ) { $e->flag = array('debug' => $this->config['debug'], 'logs_path' => $this->config['logs_path'], 'message_handle' => $this->config['message_handle'], 'system_email' => $this->config['system_email'], 'controller_type' => $this->config['controller_type']); return; } public static function setConfig( &$config ) { self::$smartConfig = & $config; } public static function newInstance($class) { try { if (!isset(self::$instance)) { $class_file = SMART_BASE_DIR . 'smart/includes/'.$class.'.php'; if(!@file_exists($class_file)) { throw new SmartInitException($class_file.' dosent exists'); } include_once($class_file); $object = new $class(); if (!($object instanceof SmartController)) { throw new SmartInitException($class.' dosent extends SmartController'); } self::$instance = $object; return $object; } else { $type = get_class(self::$instance); throw new SmartInitException('Controller instance exists: '.$type); } } catch (SmartInitException $e) { $e->performStackTrace(); } } } class SmartCache extends SmartObject { public $config; function __construct( & $config ) { $this->config = & $config; } public static function newInstance($class, & $config) { $class_file = SMART_BASE_DIR . 'smart/includes/'.$class.'.php'; if(!@file_exists($class_file)) { throw new SmartCacheException($class_file.' dosent exists'); } include_once($class_file); $object = new $class( $config ); if (!($object instanceof SmartCache)) { throw new SmartCacheException($class.' dosent extends SmartCache'); } return $object; } } SmartController::setConfig( $SmartConfig ); ?>