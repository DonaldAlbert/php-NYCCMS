<?php
/**
 * Date: 23-Jan-15
 * Time: 4:23 PM
 */

/**
 * Class dBQuery
 * Sends Queries to the Database
 */
class Module_Template_Engine implements ModulesCoreModule
{
  public function onLoad() {
    echo 'Loading Template Engine <br/>'."\n"; 
    ModulesCore::getInstance()->addInterface('Module_Routing_Engine', new RoutingListener);
  }
  
  
  public function templateMethod1($arg) {
    echo "Running templateMethod1 with arg='$arg' <br/>\n";
  }  
  
  public function templateMethod2($arg) {
    echo "Running templateMethod2 with arg='$arg' <br/>\n";
  }  
  
  public function templateMethod3($arg) {
    echo "Running templateMethod3 with arg='$arg' <br/>\n";
  }
}

class RoutingListener implements Module_Routing_EngineInterface 
{
  public function routingEvent1()
  {
    echo "routingEvent1 triggered. Listener registered by Module_Template_Engine <br/>\n";
  }
}

?>