<?php

namespace nyccms\modules;

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