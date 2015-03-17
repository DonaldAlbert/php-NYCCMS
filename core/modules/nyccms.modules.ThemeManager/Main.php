<?php
/**
 * Created by PhpStorm.
 * User: GRE-ENG
 * Date: 2/9/2015
 * Time: 6:58 PM
 */

namespace nyccms\modules;
use nyccms\core\Engine as Engine;

if (!defined('CMS_ROOT'))
  exit();

require_once('ThemeHandle.php');

/*
 * $themeEngine = ...;
 *
 *
 * --- CASE 1 --------------------------------------------
 *
 * $themeEngine->enableTheme('theme1');
 * $themeEngine->render('page1', $data);
 *
 * --- CASE 2 --------------------------------------------
 * $themeEngine->enableTheme('theme1');
 * $themeEngine->setBlockData('mainMenu', $blockData);
 * $themeEngine->render('page1', $genericData);
 *
 * --- CASE 3 --------------------------------------------
 * $themeEngine->enableTheme('theme1');
 * $themeEngine->appendBlockHtml('mainMenu', 'blockToAppend');
 * $themeEngine->render('page1', $genericData);
 *
 */

class ThemeEngine implements \nyccms\core\ModulesCoreModule {

  /**
   * Relative to the CMS root.
   */
  const THEME_FOLDER = 'themes';
  // TODO: To be deleted;

  private $themesDir;
  private $activeTheme;


  public static function validateThemeName($theme) {
    return true;
  }
  

  public static function validatePageName($themePage) {
    return true;
  }


  private function __construct($themesDir) {
    $this -> themesDir = Engine::normalizePath($themesDir);
    $this -> activeTheme = 'default';
  }


  private function getThemeRoot() {
    return $this -> themeDir . '/' . $this -> activeTheme;
  }


  private function getFilename($themePage) {
    $fileName = $this -> getThemeRoot() . '/' . $themePage . '.php';
    if (file_exists($fileName))
      return $fileName;
    else
      return '';
  }

  
  protected function renderPage($exitingPage, $content) {
    global $tc;
    
    ob_start();
    
    $tc = new ThemeHandle($this);
    $tc -> setContent($content);
    include ($exitingPage);
    unset($tc);
    
    ob_end_flush();
  }


  public function getModuleVersion() {
    return '0.1';
  }


  public function onLoad(\nyccms\core\ModulesCore $core) {
    return;
  }


  public function setTheme($themeName) {
    if ($this -> validateThemeName($themeName)) {
      $this -> activeTheme = $themeName;
      return true;
    } else {
      return false;
    }
  }
  
  
  public function getTheme() {
    return $this -> activeTheme;
  }


  /**
   * NOTE: This method resets the global variable $tc.
   *
   * @param $themeFile
   * @param $content
   *
   * @return
   */
  public function renderTheme($themeFile, $content) {
    if (!$this -> validatePageName($themeFile))
      return false;


    $themeFile = $this -> getFilename($themeFile);
    if ($themeFile) {
      $this -> renderPage($themeFile, $content);
      return true;
    } else {
      return false;
    }

  }

}
