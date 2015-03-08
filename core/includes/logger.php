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
  const LOG_FILE= 'nyccms.log';
  
  
  private $loggingLvl = 0; //Log nothing  
  private $signature;
  private $logFile = null;
  
  
  private static function generateSignature() {
    $result = microtime(true);
    return substr($result, -7);
  }
  
  public function __construct($loggingLevel) {
    $this->loggingLvl = $loggingLevel;
    $this->signature  = self::generateSignature();
    $this->logFile    = CMS_ROOT.'/'.self::LOG_FILE;
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
            $severity = '???';
            break;
    }
    
    $message = "NYCCMS $severity\t$this->signature\t".date('c')."\t$message\n";
    return error_log( $message, 3, $this->logFile);
  }
}
