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
  const ERROR   = 4;
  const WARNING = 2;
  const INFO    = 1;
  const LOG_FILE= 'nyccms.log';
  
  
  private $loggingLvl = 9999; //Log nothing  
  private $signature;
  private $logFile = null;
  
  
  /**
   * This method is used to create a unique string for each instance of a
   * logger. This string is prepended to each log-line in order to make
   * clear to the reader which lines have been written by the same logger
   * instance.
   */
  private static function generateSignature() {
    $result = microtime(true);
    return substr($result, -7);
  }
  
  
  /**
   * The constructor of the Logger class.
   * 
   * @param int $loggingLevel One of ERROR, WARNING, INFO 
   *  constants of the nyccms\core\Logger class. The logger will not log any
   *  messages that are less severe than this value.
   */
  public function __construct($loggingLevel) {
    $this->loggingLvl = $loggingLevel;
    $this->signature  = self::generateSignature();
    
    $failover         = CMS_ROOT . '/' . self::LOG_FILE;
    $this->logFile    = Engine::getSettingsCore()->get('logfile', $failover);
    if( substr($this->logFile, 0, 1) !== '/' )
      $this->logFile  = CMS_ROOT . '/' . $this->logFile; 
  }
  
  
  /**
   * Use the method to log a message.
   * NOTE: This method will produce a php Warning if the file is not writable.
   *  This is intentional behavior. We wanted to avoid checking if the logfile 
   *  is writable on every log call since this method will be called regularly
   *  in the application and normally the logfile will be accessible. Make sure
   *  the php display warnings option is off in production environments.
   * 
   * @param String $message The message to be logged.
   * @param int $severity Indicates the severity of the log message and it will
   *  be present in the log file. Should be one of ERROR, WARNING, INFO 
   *  constants of the nyccms\core\Logger class.
   * 
   * @return Boolean True on successful log, false otherwise.  
   */
  public function log($message, $severity) {
    if( $severity < $this->loggingLvl)
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
