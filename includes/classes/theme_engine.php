<?php
/**
 * Created by PhpStorm.
 * User: GRE-ENG
 * Date: 2/9/2015
 * Time: 6:58 PM
 */

class ThemeEngine {

    /**
     * Relative to the CMS root.
     */
    const THEME_FOLDER = 'themes';

    protected static $instance;

    protected $activeTheme;
    protected $theme_content;



    public static function getInstance() {
        if( !isset(ThemeEngine::$instance) )
            ThemeEngine::$instance = new ThemeEngine();

        return ThemeEngine::$instance;
    }

    protected function ThemeEngine() { $this->init(); }

    protected function init() {
        $this->activeTheme = 'default';
        $this->initRenderArray();
    }

    protected function initRenderArray(){
        $result = [
            'theme' => [
                'root' => $this->publicThemeRoot(),
                'css' => [],
            ],
            'content' => [],
        ];

        $this->theme_content = $result;
    }

    protected function getThemeRoot(){
        return CMS_ROOT . DIRECTORY_SEPARATOR . ThemeEngine::THEME_FOLDER . DIRECTORY_SEPARATOR . $this->activeTheme;
    }

    protected function getFilename($themePage) {
        $fileName = $this->getThemeRoot() . DIRECTORY_SEPARATOR . $themePage . '.php';
        if( file_exists($fileName) )
            return $fileName;
        else
            return '';
    }

    protected function publicThemeRoot() {
        return ThemeEngine::THEME_FOLDER . '/' . $this->activeTheme;
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
        $this->theme_content['theme']['css'][] = $filename;
    }

    /**
     * NOTE: This method resets the global variable $theme_content.
     *
     * @param $themeFile
     * @param $content
     *
     * @return
     */
    public function renderTheme($themeFile, $content){
        if( !$this->validatePageName($themeFile) ) return false;

        global $theme_content;
        $theme_content = $this->theme_content;
        $theme_content['content'] = $content;

        $themeFile = $this->getFilename($themeFile);
        if( $themeFile )
            include($themeFile);
//        else
//            // TODO: Handle Missing theme file situation.

        return true;
    }
} 