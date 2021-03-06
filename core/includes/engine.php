<?php
/**
 * This script defines the only static class of the NYCCMS.
 * If someone would like to customize the whole CMS, this file should most
 * probably be written from the beginning.   
 */

namespace nyccms\core;

if( !defined('CMS_ROOT') )  exit();


/**
 * The Engine class is meant to bring together and manage all the core classes
 * (core/includes/). Among its roles is to initialize and prepare those classes
 * so they are available to the modules system, to provide a globally available 
 * point for core functionalities and to provide convenience methods that will
 * be available everywhere.
 *   
 */
class Engine {
  private static $modulesCore;
  private static $logger;
  private static $events;
  private static $settings;
  private static $themes;
  private static $router;
  private static $users;
  
  
  /**
   * Since this class is static the constructor must be private.
   */
  private function __construct() { }
  
  
  // --- Engine Initiators ----------------------------------------------------
  
  
  /**
   * This method will do all the necessary initialization tasks to prepare
   * the class for usage.
   * 
   * @param String $settingsFile The file which contains the basic settings of 
   *  the CMS. This file will be read by the SettingsCore class.
   * @param int $loggingLvl The logging level of the CMS's logger. This value
   *  will be passes to the constructor of the Logger class. Look to the 
   *  documentation of the Logger class for valid values. 
   */
  private static function initiate($settingsFile, $loggingLvl) {
    self::$settings = new SettingCore($settingsFile);
    $ok = self::$settings->loadFile();
    if( !$ok ) {
      $msg = self::$settings->getSettingsFile();
      new Exception( $msg . ' file could not be read.' );
    }
    
    self::$logger = new Logger($loggingLvl);
    
    $coreDir = '/' . self::$settings->get('coreModulesDirectory');
    $expDir  = '/' . self::$settings->get('expansionModulesDirectory');
    self::$modulesCore = new ModulesCore(CMS_ROOT.$coreDir, CMS_ROOT.$expDir);
  }

  
  /**
   * This method initiates the Engine with the production configuration.
   *  - Logging level set to WARNING 
   */
  public static function initiate_production() {
    self::initiate('core/settings/settings.php', Logger::WARNING);
    self::log_info('Engine initiate_production');
  }
  
  
  /**
   * This method initiates the Engine with the debugging configuration.
   *  - Logging level set to INFO. 
   */
  public static function initiate_debug() {
    self::initiate('core/settings/settings.php', Logger::INFO);
    self::log_info('Engine initiate_debug');
  }

  
  // --- Logging facilities ---------------------------------------------------
  
  /**
   * Use this method to change the Logger object that is responsible for the 
   * logging facilities of the CMS.
   * 
   * @param Logger $logger The new logger object to be used.
   * @param String $message Optional. A message to be logged with the previous 
   *  logger. You may use this message to indicate the reason for the logger
   *  change.
   */
  public static function setLogger(Logger $logger, $message='') {
    if( self::$logger ) 
      self::log_info('Changing logger mechanism.'+$message);
    
    self::$logger = $logger;
  }
  

  /**
   * One of the main logging methods. Use this method to log information level
   * messages.
   * NOTE: This method might cause a PHP warning if the log file is not accessible.
   * 
   * @param String $message The message to be logged.
   */
  public static function log_info($message) {
    self::$logger->log($message, Logger::INFO);
  }
  
  
  /**
   * One of the main logging methods. Use this method to log warning level
   * messages.
   * NOTE: This method might cause a PHP warning if the log file is not accessible.
   * 
   * @param String $message The message to be logged.
   */
  public static function log_warn($message) {
    self::$logger->log($message, Logger::WARNING);
  }
  
  
  /**
   * One of the main logging methods. Use this method to log error level
   * messages.
   * NOTE: This method might cause a PHP warning if the log file is not accessible.
   * 
   * @param String $message The message to be logged.
   */
  public static function log_error($message) {
    self::$logger->log($message, Logger::ERROR);
  }
  
  
  /**
   * One of the main logging methods. Use this method to log error level
   * messages. Additionally this method will force the termination of the 
   * script. You may use this method to log fatal events and force script 
   * termination on one call.
   * NOTE: This method might cause a PHP warning if the log file is not accessible.
   * 
   * @param String $message The message to be logged.
   */
  public static function log_fatal($message) {
    self::$logger->log('FATAL: '.$message, Logger::ERROR);
    exit();
  }
  
  
  // --- Base module loaders -------------------------------------------------
  
  /**
   * A convenience menthod that is used to load the nyccms.modules.EventManager
   * module.
   */
  public static function loadEventEngine() {
    if( !self::$events ) {
      $done = self::$modulesCore->loadModule('nyccms.modules.EventManager');
      if( $done )
        self::$events = 
          self::$modulesCore->getModule('nyccms.modules.EventManager');
    }
  }
  
    
  // public static function loadEventEngine() {
    // if( !self::$events ) {
      // $done = self::$modulesCore->loadModule('nyccms.modules.EventManager');
      // if( $done )
        // self::$events = 
          // self::$modulesCore->getModule('nyccms.modules.EventManager');
    // }
  // }


  // --- Convenience Methods ----------------------------------------------------
  
  /**
   * This method takes a path (either relative to the CMS's root or absolute) and
   * converts it to absolute.
   * 
   * @param String $path The path we want to normalize.
   * 
   * @return String The absolute form of the path given.
   */
  public static function normalizePath($path) {
    if( substr($path, 0, 1) !== '/' )
      return CMS_ROOT . '/' . $path;
    else
      return $path; 
  }
  
  /**
   * A convenience method that returns the pre-loaded (by an initiate method)
   * instance of the ModulesCore class.
   * 
   * @return ModulesCore The instance of the main engine's ModuleCore instance. 
   */
  public static function getModulesCore() {
    return self::$modulesCore;
  }
  
  
  /**
   * A convenience method that returns the pre-loaded (by an initiate method)
   * instance of the ModulesCore class.
   * 
   * @return ModulesCore The instance of the main engine's ModuleCore instance. 
   */
  public static function getSettingsCore() {
    return self::$settings;
  }
  
}
