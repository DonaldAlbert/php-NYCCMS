<?php
/**
 * This script defines the only static class of the NYCCMS.
 * If someone would like to customize the whole CMS this file should most
 * probably be written from the beginning.   
 */

namespace nyccms\core;

if( !defined('CMS_ROOT') )  exit();



class Engine {
  private static $modulesCore;
  private static $logger;
  private static $events;
  private static $settings;
  private static $themes;
  private static $router;
  private static $users;
  
  private function __construct() { }
  
  
  // --- Engine Initiators ----------------------------------------------------
  
  
  private static function initiate($settingsFile, $loggingLvl) {
    self::$settings = new SettingCore($settingsFile);
    $ok = self::$settings->loadFile();
    if( !$ok ) {
      $msg = self::$settings->getSettingsFile();
      new Exception( $msg . ' file could not be read.' );
    }
    
    self::$logger = new Logger($loggingLvl);
    self::log_info('Engine initiate_debug');
    
    $coreDir = '/' . self::$settings->get('coreModulesDirectory');
    $expDir  = '/' . self::$settings->get('expansionModulesDirectory');
    self::$modulesCore = new ModulesCore(CMS_ROOT.$coreDir, CMS_ROOT.$expDir);
  }

  
  public static function initiate_production() {
    self::initiate('core/settings/settings.php', Logger::WARNING);
  }
  
  
  public static function initiate_debug() {
    self::initiate('core/settings/settings.php', Logger::INFO);
  }

  
  // --- Logging facilities ---------------------------------------------------
  
  public static function setLogger(Logger $logger, $message='') {
    if( self::$logger ) 
      self::log_info('Changing logger mechanism.'+$message);
    
    self::$logger = $logger;
  }
  
  
  public static function log_info($message) {
    self::$logger->log($message, Logger::INFO);
  }
  
  
  public static function log_warn($message) {
    self::$logger->log($message, Logger::WARNING);
  }
  
  
  public static function log_error($message) {
    self::$logger->log($message, Logger::ERROR);
  }
  
  
  public static function log_fatal($message) {
    self::$logger->log('FATAL: '.$message, Logger::ERROR);
    exit();
  }
  
  
  // --- Base modules loaders -------------------------------------------------
  
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


  // --- Read-Only getters ----------------------------------------------------
  
  public static function getModulesCore() {
    return self::$modulesCore;
  }
  
  
  public static function getEventEngine() {
    return self::$events;
  }
  
  
  public static function getSettingsCore() {
    return self::$settings;
  }
  
}
