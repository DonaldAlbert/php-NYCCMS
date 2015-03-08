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
  private static $themes;
  private static $router;
  private static $users;
  private static $settings;
  
  private function __construct() { }
  
  
  public static function initiate_production() {
    self::$logger = new Logger(Logger::WARNING);
    self::log_info('Engine initiate_production');
    self::$modulesCore = new ModulesCore(CMS_ROOT.'/core/modules',
      CMS_ROOT.'/expansions/modules');
  }
  
  
  public static function initiate_debug() {
    self::$logger = new Logger(Logger::INFO);
    self::log_info('Engine initiate_production');
    self::$modulesCore = new ModulesCore(CMS_ROOT.'/core/modules',
      CMS_ROOT.'/expansions/modules');
  }
  
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
  
  
  public static function getModulesCore() {
    return self::$modulesCore;
  }
  
  
}
