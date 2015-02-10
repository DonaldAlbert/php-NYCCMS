<?php
/**
 * Date: 23-Jan-15
 * Time: 4:23 PM
 * Author: DonaldAlbert
 */

  //============================================================================
  // vvv - T O D O  - vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
  // 1) TODO Allow modules to use namespaces.
  // 2) TODO Implement decoupled implementations of interfaces. i.e. The interfaces
  //    and their implementations are included only if both modules are loaded.
  // 3) TODO Build block functionality on the template engine 
  //============================================================================
    

 
 
/**
 * Class ModulesCore
 * Implements the module system of the CMS.
 * Current features:
 *    - Each module provides one main class and one interface.
 *    - All the provided interfaces are loaded initially, even if the 
 *      corresponding module is not loaded.
 *    - All the modules are found inside their own folder (with the name of 
 *      the module) which is inside the folder $modulesDir.
 *    - The modules names are matching "^[a-zA-Z_][a-zA-Z0-9_]*$".
 *     
 */
class ModulesCore
{
    const MODULES_DIR = "modules/";
    
    private static $instance;
    private $modules = [];
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
      $regexp = '/^([a-zA-Z_][a-zA-Z0-9_]*\.)*[a-zA-Z_][a-zA-Z0-9_]*$/';
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
      return $this->getModuleDir($modName).'/Main.php';
    }
    
    
    /**
     * User this method to get the namespace of the module's main class.
     */
    public function getModuleNamespace($modName) {
      $result = $this->getModuleClass($modName);
      $pos = strrpos($result, '\\');
      
      return substr($result, 0, $pos); 
    }
    
    
    /**
     * Use this method to get the module's main class fully classified name.
     */
    public function getModuleClass($modName) {
      return str_replace('.', '\\', $modName);
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
        $className = $this->getModuleClass($modName);
        if( !is_subclass_of($className ,'ModulesCoreModule') )
          throw new Exception('Module class '.$className.
              ' not implementing ModulesCoreModule interface'
          );
        $this->modules[$modName] = new $className();
        
        try {
          $this->modules[$modName]->onLoad($this);
        } catch (Exception $e) {}
        
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
    
}


/**
* An interface that all the modules should implement.
*/
interface ModulesCoreModule {
  public function getModuleName();
  public function onLoad(ModulesCore $core);
  public function onLoadingDone(ModulesCore $core);
}


/**
* An interface that all the module interfaces should extend.
*/
interface ModulesCoreInterface {
  public function getImplementingModule();
}

