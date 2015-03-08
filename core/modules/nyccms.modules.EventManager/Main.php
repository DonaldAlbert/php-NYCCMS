<?php

namespace nyccms\modules;
use nyccms\core\Engine as Engine;

if( !defined('CMS_ROOT') )  exit();


class EventManager implements \nyccms\core\ModulesCoreModule {
  
  /**
   * An array that hold all the necessary stuff to invoke the method.
   * e.g. [
   *    'event1' => [
   *      ['ClassA', $thisObj, 'methodName1'],
   *      ['ClassB', $thisObj, 'methodName2'],
   *    ],
   *  ] 
   */
  private $events = [];
  
  public function getModuleVersion() { return '0.1'; }
  
  public function onLoad(\nyccms\core\ModulesCore $core) { return; }
  
  
  /**
   * User this method to check if an event exists.
   */
  public function eventExists($event){
    return key_exists($event, $this->events);
  }
  
  
  /**
   * Use this method to register a new event.
   * 
   * @return Boolean True if the new event was registered successfully, False
   *  if the event already exists. 
   */
  public function registerEvent($event) {
    Engine::log_info("Creating event '$event'.");
    if( key_exists($event, $this->events) ) 
      return false;
    
    $this->events[$event] = [];
    
    return true;
  }
  
  
  /**
   * Use this method to unbind a callbackMethod.
   */
  public function unregisterEvent($event){
    // TODO: Implementation pending
    return;
  }
  
  
  /**
   * Use this method to bind a method (callback method) to a registered event.
   * The callback method could either be a static method or an instance method
   * but it has to be a public method.
   * 
   * @param String $event This is the event with which your callback method 
   *  will bind with.
   * @param mixed Either a class name (String) in case your callback method is 
   *  static or an object that has your callback method as an instance method.
   * @param String $callabackMethod This is the name of your callback method.
   * 
   * @return Boolean True the callback method was successfully binded, false
   *  otherwise.  
   */
  public function bindEvent($event, $obj, $callbackMethod) {
    Engine::log_info("Binding '$callbackMethod' to '$event' event.");
    if( is_string($obj) ){
      $classname = $obj;
      $obj = null;
    } else {
      $classname = get_class($obj);
    }
    
    try {
      $method = new \ReflectionMethod( $classname, $callbackMethod );
      if( key_exists($event, $this->events) && 
          $method->isPublic() &&
          (is_null($obj) xor !$method->isStatic()) 
      ) {
        $this->events[$event][] = [$classname, $obj, $callbackMethod];
        
        return true;
      }      
    } catch ( Exception $e ) { }
    

    Engine::log_warn('Binding FAILED.');
    return false;
  }
  
  
  /**
   * Use this method to unbind a callbackMethod.
   */
  public function unbindEvent($obj, $event=null, $callbackMethod=null){
    // TODO: Implementation pending
    return;
  }
  
  
  public function triggerEvent($event, $args=[]) {
    Engine::log_info("Triggering event '$event'.");
    if( !key_exists($event, $this->events) ) {
      Engine::log_fatal("Call to a non existing event '$event'.");
    }
        
    $results = [];
    foreach ($this->events[$event] as $callback) {
      try {
        $method = new \ReflectionMethod( $callback[0], $callback[2] );
        $results[] = $method->invokeArgs ( $callback[1], $args ); 
      } catch( Exception $e ) {
        Engine::log_error("Exception raised while triggering event '$event'. ".e);
      }
    }
    
    return $results;
  }
  
}