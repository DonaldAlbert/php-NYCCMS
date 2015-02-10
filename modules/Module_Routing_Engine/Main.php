<?php
/**
 * Date: 23-Jan-15
 * Time: 4:23 PM
 */

/**
 * Class dBQuery
 * Sends Queries to the Database
 */
class Module_Routing_Engine implements ModulesCoreModule
{
  public function getModuleName() { return 'Module_Routing_Engine';}
  
  
  public function onLoad(ModulesCore $core) {
    echo 'Loading Routing Engine <br/>'."\n"; 
  }
  
  
  public function onLoadingDone(ModulesCore $core) {}
  
  
  public function getInterfaces() {}
  
  
  public function loadInterface($moduleName) {}
  
  
  public function routingMethod1($arg) {
    echo "Running routingMethod1 with arg='$arg' <br/>\n";
  }  
  
  public function routingMethod2($arg) {
    echo "Running routingMethod2 with arg='$arg' <br/>\n";
  }  
  
  public function routingMethod3($arg) {
    echo "Running routingMethod3 with arg='$arg' <br/>\n";
  }
}

?>