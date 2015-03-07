<?php

/**
 * Date: 23-Jan-15
 * Time: 4:23 PM
 * Author: DonaldAlbert
 */

namespace nyccms\core;
 

/*
 * TODO: Make getModuleClass and getModuleNamespace static.
 */    

 
 
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
    /**
     * Used along with the EXPANSION_DIR and AUTO_DIR, this constant designate
     * the folder that the ModulesCore will look for module files.
     * Using CORE_DIR means that the ModulesCore will look only into the
     * core directory (given in the constructor) to find modules. 
     */
    const CORE_DIR = 1;

    /**
     * Used along with the CORE_DIR and AUTO_DIR, this constant designate
     * the folder that the ModulesCore will look for module files.
     * Using EXPANSION_DIR means that the ModulesCore will look only into the
     * expansion directory (given in the constructor) to find modules. 
     */
    const EXPANSION_DIR = 2;

    /**
     * Used along with the EXPANSION_DIR and CORE_DIR, this constant designate
     * the folder that the ModulesCore will look for module files.
     * Using AUTO_DIR means that the ModulesCore will look first into the
     * expansion directory (given to the constructor) to find modules and if
     * the module is not found, then the ModulesCore will look into the core 
     * directory (also given to the constructor) to find the module. 
     */
    const AUTO_DIR = 4;
    
    
    private static $instance;
    private $modules = [];
    private $lazyLoadModules = true;
    private $coreDir = '';
    private $expDir = '';
    private $moduleLookupMethod = ModulesCore::AUTO_DIR;
    
    /**
     * An array which holds data about interfaces with the following format.
     *
     * [
     *   'moduleName' => [
     *     'provided' => ['ver1', 'ver2'],
     *     'implemented' => [
     *       'otherModule' => ['ver2', 'ver3'],
     *     ],
     *     'loaded' => [
     *       'moduleName' => ['ifInstance1','ifInstance2','ifInstance2'],
     *     ],
     *   ],
     * ];
     */
    private $interfaces = [];
    


    /**
     * The constructor of the class initializes the directories where the modules are
     * found. The given directories are appended with the value fo
     */
    public function __construct($coreDir, $expDir = '') {
        if( !is_dir($coreDir) )
          throw new \Exception('"' . $coreDir . '" directory does not exists.');
        
        $this->coreDir = rtrim($coreDir, '\\/');
        $this->expDir  = rtrim($expDir, '\\/');
    }
    
    
    /**
     * Use this method to validate the name of the module.
     * This name is applied to both module's folder, module's
     * Interface and module's main class.
     * 
     * @param String $modName The name to be validated.
     * @return Boolean True if the module name is valid, False
     *  otherwise.
     */
    public static function validateModuleName($modName) 
    { 
      //^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$ Valid classname reqexp.
      $regexp = '/^([a-zA-Z_][a-zA-Z0-9_]*\.)*[a-zA-Z_][a-zA-Z0-9_]*$/';
      $result = preg_match($regexp, $modName);
      return ($result === 1);
    }
    
    
    /**
     * Use this method to get the module's main class fully classified name.
     */
    public static function getModuleClass($modName) {
      return str_replace('.', '\\', $modName);
    }
    
    
    /**
     * User this method to get the namespace of the module's main class.
     */
    public static function getModuleNamespace($modName) {
      $result = self::getModuleClass($modName);
      $pos = strrpos($result, '\\');
      
      return substr($result, 0, $pos); 
    }
    
    
    /**
     * Defines the lookup method to be used to find requested modules. Valid
     * values are CORE_DIR, EXPANSION_DIR and AUTO_DIR. The default value is 
     * set to AUTO_DIR.
     * 
     * @return Boolean true if the new lookup method is successfully set, false
     *  otherwise (this happens when invalid values are given).
     */
    public function setLookupMethod($newMethod) {
      switch ($newMethod) {
          case self::CORE_DIR:
          case self::EXPANSION_DIR:
          case self::AUTO_DIR:
            $this->moduleLookupMethod = $newMethod;
            return true;
          default:
            return false;
      }
    }
    
    
    /**
     * Returns the currently set lookup method.
     * 
     * @return int The currently used method.
     */
    public function getLookupMethod() {
      return $this->moduleLookupMethod;
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
     * will return the path of the module's folder.
     * This method is dependent on the value of the LookupMethod. If the 
     * value is set to AUTO_DIR this method will check if the expansion
     * directory exists for this module. If it does, it will return the 
     * expansion directory, else it will return the base directory. In any
     * other case the method will return the directory specified by the lookup
     * method, either it exists or not.
     * Note: This method assumes the same base dir as the parameters given 
     *   during the constructor call.
     * 
     * @param String $modName The name of the module of which the dir we want.
     * 
     * @return String A String with the directory of the module.
     */
    public function getModuleDir($modName)
    {
      switch ($this->moduleLookupMethod) {
        case self::CORE_DIR:
          return $this->coreDir . '/' . $modName;
        case self::EXPANSION_DIR:
          return $this->expDir . '/' . $modName;
        default:
          $expDir = $this->expDir . '/' . $modName;
          if( $this->expDir && is_dir($expDir) )
            return $expDir;
          else 
            return $this->coreDir . '/' . $modName;
      }
    }
    
    
    /**
     * Use this method to determine which directory is used for a specific 
     * module. This method is rather useful in case the AUTO_DIR lookup method
     * is used, in order to determine which directory will be used to load 
     * module.
     */
    public function isExpansionDirUsed($modName) {
      switch ($this->moduleLookupMethod) {
        case self::CORE_DIR:
          return false;
        case self::EXPANSION_DIR:
          return true;
        default:
          $expDir = $this->expDir . '/' . $modName;
          if( $this->expDir && is_dir($expDir) )
            return true;
          else 
            return false;
      }
    }
    
    
    /**
     * Given the name of the module (case sensitive) this method
     * will return the relative path of the modules main script.
     * 
     */
    public function getModuleMain($modName)
    {
      return $this->getModuleDir($modName).'/Main.php';
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
          self::validateModuleName($modName) &&
          $this->moduleExists($modName) 
      ) 
      {
        require_once($this->getModuleMain($modName));
        $className = self::getModuleClass($modName);
        if( !is_subclass_of($className ,'\nyccms\core\ModulesCoreModule') )
          throw new \Exception('Module class '.$className.
              ' not implementing ModulesCoreModule interface'
          );
        $this->modules[$modName] = new $className();
        
        try {
          $this->modules[$modName]->onLoad($this);
        } catch (\Exception $e) {}
        
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
  public function getModuleVersion();
  public function onLoad(ModulesCore $core);
  public function onLoadingDone(ModulesCore $core);
}

