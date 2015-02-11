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
  private $impl = null;
  
  public function getModuleName() { return 'Module_Routing_Engine';}
  
  
  public function getModuleVersion() { return '0.0'; }
  
  
  public function getCompatibility() { return ['0.0',]; }
  
  
  public function getImplementation($providingModule, $version=null ) {
    switch ($providingModule) {
        case 'core.testing.TemplateEngine':
          if( !$this->impl ) {
            require_once('core.testing.TemplateEngineImpl.php');
            return new TemplateEngineImpl();
          } else
            return $this->impl; 
          break;
      
        default:
          return null; 
          break;
    }
  }
  
  
  public function getImplementationVersions($providingModule) {
    switch ($providingModule) {
      case 'core.testing.TemplateEngine':
        return ['0.0'];
    
      default:
        return []; 
    } 
  }
  
  
  public function loadInterface() {}

  
  public function onLoad(ModulesCore $core) {
    $core->registerImplementedInterface('core.testing.TemplateEngine', $this->getModuleName());
    echo 'Loading Routing Engine <br/>'."\n"; 
  }
  
  
  public function onLoadingDone(ModulesCore $core) {}
  
  
  public function getInterfaces() {}
  
  
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