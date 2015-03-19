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

require_once ('ThemeHandle.php');

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

class ThemeManager implements \nyccms\core\ModulesCoreModule {

  /**
   * Relative to the theme's root.
   */
  const STATIC_DIR = 'static';
  const BLOCKS_DIR = 'blocks';
  const SKELETONS_DIR = 'skeletons';
  const PAGES_DIR = 'pages';

  private $themesDir;
  private $activeTheme;

  public static function validateThemeName($theme) {
    return true;
  }

  public static function validatePageName($themePage) {
    return true;
  }

  public function __construct() {
    $this->themesDir = Engine::normalizePath('themes');
    $this->activeTheme = 'default';
  }

  private function getFilename($themePage) {
    $fileName = $this->getPagesDir() . '/' . $themePage . '.php';
    if (file_exists($fileName))
      return $fileName;
    else
      return '';
  }

  private function renderPage($exitingPage, $content) {
    global $tc;

    ob_start();

    $tc = new ThemeHandle($this);
    $tc->setContent($content);
    include ($exitingPage);
    $tc->echoExtends();
    unset($tc);

    ob_end_flush();
  }

  public function getThemeRoot() {
    return $this->themesDir . '/' . $this->activeTheme;
  }

  public function getBlocksDir() {
    return $this->getThemeRoot() . '/' . self::BLOCKS_DIR;
  }

  public function getSkeletonsDir() {
    return $this->getThemeRoot() . '/' . self::SKELETONS_DIR;
  }

  public function getStaticDir() {
    return $this->getThemeRoot() . '/' . self::STATIC_DIR;
  }

  public function getPagesDir() {
    return $this->getThemeRoot() . '/' . self::PAGES_DIR;
  }

  public function getModuleVersion() {
    return '0.1';
  }

  public function onLoad(\nyccms\core\ModulesCore $core) {
    return;
  }

  public function setTheme($themeName) {
    if ($this->validateThemeName($themeName)) {
      $this->activeTheme = $themeName;
      return true;
    } else {
      return false;
    }
  }

  public function getTheme() {
    return $this->activeTheme;
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
    if (!$this->validatePageName($themeFile))
      return false;

    $themeFile = $this->getFilename($themeFile);
    if ($themeFile) {
      $this->renderPage($themeFile, $content);
      return true;
    } else {
      return false;
    }

  }

}
