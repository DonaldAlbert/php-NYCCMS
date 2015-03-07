<?php

namespace nyccms\core;

if( !defined('CMS_ROOT') )  exit();



class Engine {
  private static $instance = null;
  
  private $modulesCore;
  
  
  private function __construct() { }
  
  
  /**
   * Use this static method get the singleton instance of the Engine class.
   */
  public static function getInstance() {
    if( !self::$instance )
      self::$instance = new Engine();
    
    return self::$instance;
  }
  
  
  public function initiate() {
    $this->modulesCore = new ModulesCore(CMS_ROOT.'/core/modules',
      CMS_ROOT.'/expansions/modules');
  }
  
  
  public function getModulesCore() {
    return $this->modulesCore;
  }
  
  
}
