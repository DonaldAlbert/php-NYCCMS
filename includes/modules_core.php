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
    private $moduleDir = ModulesCore::MODULES_DIR;
    private $lazyLoadModules = true;
    
    
    /**
    * Use this to get the single instance of the ModulesCore.
    * If the instance is not created yet, the method will create
    * one the first time it is called.
    * 
    * @return ModulesCore The instance of the ModulesCore object.
    */
    public static function getInstance()
    {
      if( !ModulesCore::$instance ) ModulesCore::$instance = new ModulesCore();
      
      return ModulesCore::$instance;
    }
    
    
    /**
    * Use this method to get the name of the module's interface
    * from the module's folder name.
    * 
    * @return String The name of the module's interface.
    */
    public static function Folder2Interface($foldername)
    {
      return $foldername.'Interface';
    }
    
    
    /**
    * Use this method to get the folder's name from the
    * name of the modules interface.
    * 
    * @return String The name of the module's folder.
    */
    public static function Interface2Folder($foldername)
    {
      return substr($foldername, 0, -9);
    }
    
    
    /**
    * Use this method to validate the name of the module.
    * This name is applied to both module's folder, module's
    * Interface and module's main class.
    * 
    * @param String $modName The name to be validated.
    * @return Boolean True if the module name is valid, False
    *     otherwise.
    */
    public static function validateModuleName($modName) 
    { 
      //^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$ Valid classname reqexp.
      $regexp = '/^[a-zA-Z_][a-zA-Z0-9_]*$/';
      $result = preg_match($regexp, $modName);
      return ($result === 1);
    }
    
    
    /**
    * When lazy module loading is set to true, if a user tries to get a 
    * module (via the getModule method) which is not loaded, the system 
    * will load the module at that time instead of returning a false value.
    * 
    * @param boolean $value Default(true). The new status of the feature.
    */
    public function lazyLoadModules($value = true)
    {
      $this->lazyLoadModules = (bool) $value;
    }
    
    
    /**
    * Given the name of the module (case sensitive) this method
    * will return the relative path of the modules folder.
    * Note: This method assumes the same base dir as the MODULES_DIR class
    *   constant.
    */
    public function getModuleDir($modName)
    {
      return $this->moduleDir.$modName;
    }
    
    
    /**
    * Given the name of the module (case sensitive) this method
    * will return the relative path of the modules main script.
    * Note: This method assumes the same base dir as the MODULES_DIR class
    *   constant.
    */
    public function getModuleMain($modName)
    {
      return $this->getModuleDir($modName).'\Main.php';
    }
    
    
    /**
    * Given the name of the module (case sensitive) this method
    * will return the relative path of the modules main script.
    * Note: This method assumes the same base dir as the MODULES_DIR class
    *   constant.
    */
    public function getModuleInterface($modName)
    {
      return $this->getModuleDir($modName).'\Interface.php';
    }
    
    
    /**
    * Checks if the module's folder and main file exists.
    * 
    * @return Boolean True if the folder and file exist, False otherwise.
    */
    public function moduleExists($modName)
    {
      $main     = $this->getModuleMain($modName);
      $result   = (
        is_dir($this->getModuleDir($modName)) &&
        file_exists($main) &&
        !is_dir($main)
      );
      return $result;
    }
    
    
    /**
    * Loads the Main.php script of a module. This method also invokes the 
    * module's onLoad method.
    * TODO: Implement failsafes for possible errors during new module import.
    * 
    * @param $modName The module name.
    * @return boolean True if the script was included successfully.
    */
    public function loadModule($modName)
    {
      if( !array_key_exists($modName, $this->modules) &&
          $this->validateModuleName($modName) &&
          $this->moduleExists($modName) 
      ) 
      {
        require_once($this->getModuleMain($modName));
        if( !is_subclass_of($modName ,'ModulesCoreModule') )
          throw new Exception('Module class '.$modName.
              ' not implementing ModulesCoreModule interface'
          );
        $this->modules[$modName] = new $modName();
        
        try {
          $this->modules[$modName]->onLoad($this);
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
          $this->moduleExists($modName) &&
          file_exists($this->getInterfaceName($modName))
      )
      {
          require_once($this->getModuleInterface($modName));
          $this->implementedInterfaces[$modName] = [];
          return True;
      }
      
      return False;
    }
    
    
    /**
    * Use this method to get the instance of a loaded module. If the module
    * is not currently loaded and lazy module loading feature is enabled
    * (i.e. $this->lazyLoadModules(true);), this method will try to load
    * the module first then it will return it.
    * 
    * @param $modName The name of the module we want to get.
    * @return mixed The found module(ModulesCoreModule) or false if the 
    *     requested module could not be found.
    */
    public function getModule($modName)
    {
      if( array_key_exists($modName, $this->modules) )
        return $this->modules[$modName];
      elseif( $this->lazyLoadModules &&
              $this->loadModule($modName) 
      )
        return $this->modules[$modName];
    
      return false;
    }
    
    //====================================================================
    // vvv - TODO - vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
    //====================================================================
    
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
      if( $handle = opendir($this->moduleDir) ) {
        
        try {
          while( $entry = readdir($handle) ) {
            if( substr($entry, 0, 1) == '.' ) continue;
          
            $dir = $this->moduleDir . $entry . '/';
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
  public function onLoad(ModulesCore $core);
  public function onLoadingDone(ModulesCore $core);
}

?>