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
  
  private $loggingLvl = 0; //Log nothing
  
  public function Logger($loggingLevel) {
    $this->logginLvl = $loggingLevel;
  }
  
  
  public function log($message, $severity) {
    if( $severity > $this->loggingLvl)
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
