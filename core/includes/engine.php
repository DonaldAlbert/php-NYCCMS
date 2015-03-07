<?php

class Engine {
  private static $instance = null;
  
  
  private function _construct() {}
  
  
  /**
   * Use this static method get the singleton instance of the Engine class.
   */
  public static function getInstance() {
    if( !self::$instance )
      self::$instace = new Engine();
    
    return self::$instance;
  }
}
