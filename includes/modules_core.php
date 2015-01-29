<?php
/**
 * Date: 23-Jan-15
 * Time: 4:23 PM
 */

 
/**
 * Class dBQuery
 * Sends Queries to the Database
 */
class ModulesCore
{
    const MODULES_DIR = "modules/";
    
    private static $instance;
    
    private $modules = [];
    private $implementedInterfaces = [];
    
    
    
    public static function getInstance()
    {
      if( !ModulesCore::$instance ) ModulesCore::$instance = new ModulesCore();
      
      return ModulesCore::$instance;
    }
    
    
    
    public function validateModuleName($modName) 
    {
      //TODO
      return true;
    }
    
    
    public function getModuleDir($modName)
    {
      return ModulesCore::MODULES_DIR.strtolower($modName);
    }
    
    
    public function getModuleMain($modName)
    {
      return $this->getModuleDir($modName).'\Main.php';
    }
    
    
    public function getModuleInterface($modName)
    {
      return $this->getModuleDir($modName).'\Interface.php';
    }
    
    
    public function moduleExists($modName)
    {
      return is_dir($this->getModuleDir($modName));
    }
    
    
    /**
    * Loads the Main.php script of a module.
    * 
    * @param $modName The module name.
    * @return boolean True if the script was included successfully.
    */
    public function loadModule($modName)
    {
      if( !array_key_exists($modName, $this->modules) &&
          $this->validateModuleName($modName) &&
          $this->moduleExists($modName) 
      ) {
        require_once($this->getModuleMain($modName));
        $this->modules[$modName] = new $modName();
        try {
          $this->modules[$modName]->onLoad();
        } catch (Exception $e) {}
        return True;
      }
      
      return False;
    }
    
    
    /**
    * Loads the Interface.php script of a module.
    * 
    * @param $modName The module name.
    * @return boolean True if the script was included successfully.
    */
    public function loadInterface($modName)
    {
      if( !array_key_exists($modName, $this->implementedInterfaces) &&
          $this->validateModuleName($modName) &&
          $this->moduleExists($modName) 
      ) {
        require_once($this->getModuleInterface($modName));
        $this->implementedInterfaces[$modName] = [];
        return True;
      }
      
      return False;
    }
    
    
    public function getModule($modName)
    {
      if( array_key_exists($modName, $this->modules) )
        return $this->modules[$modName];
      
      return false;
    }
    
    
    public function registerInterface($modName, $ifObj)
    {
      if( !array_key_exists($modName, $this->implementedInterfaces) ||
          ( array_key_exists($modName, $this->implementedInterfaces) &&
            !in_array($ifObj, $this->implementedInterfaces[$modName])
          )
      ) {
        $this->implementedInterfaces[$modName][] = $ifObj;
        return true;
      }
      
      return false;
    }
    
    
    public function unregisterInterface($modName, $ifObj)
    {
      if( array_key_exists($modName, $this->implementedInterfaces) &&
          in_array($ifObj, $this->implementedInterfaces[$modName]) 
      ) {
        $key = array_search($ifObj, $this->implementedInterfaces[$modName], true);
        unset($this->implementedInterfaces[$modName][$key]);
        return true;
      }
      
      return false;
    }
    
    
    public function getInterfaces($modName)
    {
      if( array_key_exists($modName, $this->implementedInterfaces) ) 
      {
        return $this->implementedInterfaces[$modName];
      }
      
      return false;
    }
    
    
    public function loadInterfaces(){
      if( $handle = opendir(ModulesCore::MODULES_DIR) ) {
        
        try {
          while( $entry = readdir($handle) ) {
            if( substr($entry, 0, 1) == '.' ) continue;
          
            $dir = ModulesCore::MODULES_DIR . $entry . '/';
            $interface = $dir.'Interface.php';
            if( file_exists($interface) &&
                !is_dir($interface)
            ) {
              include_once($interface);
            }
          }
        } catch (Exception $e) {}

        closedir($handle);
      }
    }
    
}


interface ModulesCoreModule {
  public function onLoad();
}

?>