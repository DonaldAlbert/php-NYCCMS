<?php
/**
 * Date: 23-Jan-15
 * Time: 4:23 PM
 */

namespace core\testing;
 
/**
 * Class dBQuery
 * Sends Queries to the Database
 */
class TemplateEngine implements \ModulesCoreModule
{
  
  public function getModuleName() { return 'core.testing.TemplateEngine'; }
  
  
  public function getModuleVersion() { return '0.0'; }
  
  
  public function getCompatibility() { return ['0.0',]; }
  
  
  public function getImplementation($providingModule, $version=null ) { return null; }
  
  
  public function getImplementationVersions($providingModule) { return []; }
  
  
  public function loadInterface() { require_once('Interface.php'); }
  
  
  public function onLoad(\ModulesCore $core) {
    $core->registerProvidedInterface($this->getModuleName());
    echo 'Loading Template Engine <br/>'."\n"; 
  }
  
  
  public function onLoadingDone(\ModulesCore $core) {}
  
  
  
  
  public function templateMethod1($arg) {
    echo "Running templateMethod1 with arg='$arg' <br/>\n";
  }  
  
  
  public function templateMethod2($arg) {
    echo "Running templateMethod2 with arg='$arg' <br/>\n";
    $listeners = \ModulesCore::getInstance()->getImplementations($this->getModuleName());
    foreach( $listeners as $listener )
      $listener->event1();
  }  
  
  
  public function templateMethod3($arg) {
    echo "Running templateMethod3 with arg='$arg' <br/>\n";
  }
}

?>