<?php

namespace nyccms\core;

if( !defined('CMS_ROOT') )  exit();


/**
 * This is the main logging facility of the NYCCMS.
 * 
 * Logger acknowledges three(3) kinds of log messages:
 *  - ERROR: When something should not have happened.
 *  - WARNING: When something might have been misconfigured
 *  - INFO: When everything is Ok. Could be used for debugging purposes.
 */
class Logger {
  const ERROR   = 1;
  const WARNING = 2;
  const INFO    = 4;
  
  
  private static $logger = null;
  
  private $logginLvl = 0; //Log nothing
  
  
    
  private static function getInstance() {
    if( !$logger )
      self::$logger = new Logger(self::WARNING);
    
    return self::$logger;
  }
  
  
  protected static function getLogger() {
    return self::$logger;
  }
  
  
  public static function setLogger(Logger $logger) {
    if( self::$logger ) 
      self::log('Changing logger implementation.', self::INFO);
    
    self::$logger = $logger;
  }
  
  
  public static function loggerEquals(Logger $logger) {
    return (self::$logger === $logger);
  }
  
  
  public static function log($message, $severity) {
    self::getInstance()->log_msg($message, severity);
  }
  
  
  public function Logger($loggingLevel) {
    $this->logginLvl = $loggingLevel;
  }
  
  
  public function log_msg($message, $severity) {
    if( $severity > $this->logginLvl)
      return false;

    switch ($severity) {
        case self::ERROR:
            $severity = 'ERROR';
            break;
        case self::WARNING:
            $severity = 'Warning';
            break;
        case self::INFO:
            $severity = 'info';
            break;
        default:
            $severity = 'unknown';
            break;
    }
    
    return error_log('NYCCMS '.$severity."\t".$message);
  }
}
