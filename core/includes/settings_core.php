<?php

namespace nyccms\core;

if( !defined('CMS_ROOT') )  exit();


/**
 * This class provides a very basic settings management system. The class
 * offers the functionality of loading special .php files that contain the
 * settings and provide them to the application through its methods.
 * The above functionality is read-only, meaning that the actual .php file
 * cannot be altered from within the application. 
 * There is some limited functionality to change a recovered setting, but is an
 * option that will last until the termination of the current script/runtime.  
 */
class SettingCore {
  
  private $settingsFile;
  private $currentSettings;
  
  
  /**
   * The main constructor of the class.
   * NOTE: Remember to call loadFile method after instantiation to actually
   *  load the settings file. 
   * 
   * @param String $fileName The absolute or relative path to the settings
   *  file. If a relative path is provided the base of it will be the content
   *  of the CMS_ROOT php constant. This file must be readable.
   */
  public function __construct($fileName){
    $first_letter = substr($fileName, 0, 1);
    if( $first_letter == '/' || $first_letter == '\\' )
      $this->settingsFile = $fileName;
    else
      $this->settingsFile = CMS_ROOT.'/'.$fileName;
  }
  
  
  /**
   * This method must be called after the instantiation of the class in order
   * for the settings file (provision in the constructor) to be loaded.
   * 
   * @return boolean True if the settings loading was successful, false otherwise.
   */
  public function loadFile(){
    if( !file_exists($this->settingsFile) )
      return false;

    include($this->settingsFile);
    $this->currentSettings = &$settings;
    
    return true;
  }
  
  
  /**
   * Use this method to get a reference to a specific setting value.
   * 
   * @param String $key The name/key of the setting we are interested in.
   * @param mixed $default The value to be returned by this method in case the
   *  settings we are looking for is not set or the settings file is not loaded.
   *  Default = null
   * 
   * @return mixed The actual value of the setting or the value of the $default
   *  parameter.
   */
  public function &get($key, $default=null) {
    if( !$this->currentSettings || !key_exists($key, $this->currentSettings) )
      return $default;
    
    return $this->currentSettings[$key];
  }
  
  
  /**
   * A simple accessor to read the settings file associated with this object.
   * 
   * @return The currently associated settings file.  
   */
  public function getSettingsFile() {
    return $this->settingsFile;
  }
}
