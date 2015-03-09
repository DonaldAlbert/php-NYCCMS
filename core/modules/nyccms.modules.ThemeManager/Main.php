<?php
/**
 * Created by PhpStorm.
 * User: GRE-ENG
 * Date: 2/9/2015
 * Time: 6:58 PM
 */
 
namespace nyccms\modules;

if( !defined('CMS_ROOT') )  exit();


class ThemeEngine {

    /**
     * Relative to the CMS root.
     */
    const THEME_FOLDER = 'themes'; // TODO: To be deleted;

    private $themesDir;
    private $activeTheme;
    private $blockStack;
    public $content;



    private function __construct($themesDir) { 
      $this->themesDir = $themesDir;
      $this->activeTheme = 'default';
      $this->initRenderArray();
      $this->blockStack = [];
    }

    private function initRenderArray(){
        $result = [
            'theme' => [
                'root' => $this->publicThemeRoot(),
                'css' => [],
            ],
            'content' => [],
            'blocks' => [],
        ];

        $this->content = $result;
    }
    
    public function getModuleVersion() { return '0.1';}
    
    public function onLoad(\nyccms\core\ModulesCore $core) { return; }

    protected function getThemeRoot(){
        return CMS_ROOT . '/' . ThemeEngine::THEME_FOLDER . '/' . $this->activeTheme;
    }

    protected function getFilename($themePage) {
        $fileName = $this->getThemeRoot() . '/' . $themePage . '.php';
        if( file_exists($fileName) )
            return $fileName;
        else
            return '';
    }

    protected function publicThemeRoot() {
        return ThemeEngine::THEME_FOLDER . '/' . $this->activeTheme;
    }

    protected function renderPage($exitingPage) {
        global $tc;
        $tc = $this;
        ob_start();
        include($exitingPage);
        ob_end_flush();
    }

    public function startBlock($name){
      // TODO: Proper implementation pending.
      if( in_array($name, $this->blockStack) )
        throw new Exception("Doublicate blocks within the same template.(Blockname = $name)", 1);
      
      array_push($this->blockStack, $name);
      ob_start();
    }

    public function endBlock() {
      // TODO: Proper implementation pending.
      $name = array_pop($this->blockStack);
      $this->content['blocks'][$name] = ob_get_contents();
      ob_end_clean();
    }

    public function validateThemeName($theme){
        return true;
    }

    public function validatePageName($themePage) {
        return true;
    }

    public function setTheme($themeName) {
        if( $this->validateThemeName($themeName) ) {
            $this->activeTheme = $themeName;
            return true;
        } else {
            return false;
        }
    }

    public function addCss($filename) {
        $this->content['theme']['css'][] = $filename;
    }

    /**
     * NOTE: This method resets the global variable $tc.
     *
     * @param $themeFile
     * @param $content
     *
     * @return
     */
    public function renderTheme($themeFile, $content){
        if( !$this->validatePageName($themeFile) ) return false;

        $this->content['content'] = $content;

        $themeFile = $this->getFilename($themeFile);
        if( $themeFile )
            $this->renderPage($themeFile);
//        else
//            // TODO: Handle Missing theme file situation.

        return true;
    }
} 