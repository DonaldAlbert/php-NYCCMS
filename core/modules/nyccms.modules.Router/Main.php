<?php

namespace nyccms\modules;
use nyccms\core\Engine as Engine;

if( !defined('CMS_ROOT') )  exit();


class Router implements \nyccms\core\ModulesCoreModule {
  
  public function getModuleVersion() { return '0.1';}
  
  public function onLoad(\nyccms\core\ModulesCore $core) {
    echo 'Loading Router <br/>'."\n"; 
  }
  
  
  public function parseRequest() {
    echo 'Parsing URL request';
  }
  
}