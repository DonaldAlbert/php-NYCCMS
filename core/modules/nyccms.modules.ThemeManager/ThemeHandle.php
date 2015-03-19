<?php

namespace nyccms\modules;
use nyccms\core\Engine as Engine;

if (!defined('CMS_ROOT'))
  exit();

/**
 *
 */
class ThemeHandle {
  private $core;
  private $content;
  private $publicRoot;
  private $blockStack;
  private $blocks;
  private $skeleton;
  private $echoBlocks;


  public function __construct(ThemeManager $core) {
    $this->core = $core;
    $this->blocks = [];
    $this->blockStack = [];
    $this->echoBlocks = false;
  }


  private function getRoot() {
    if (!($this->publicRoot))
      $this->publicRoot = Engine::getSettingsCore()->get('public_root', '/');

    return $this->publicRoot;
  }


  public function setContent($content) {
    $this->content = $content;
  }


  public function getContent($key, $defalut = '') {
    if (key_exists($key, $this->content))
      return $this->content[$key];
    else
      return $default;
  }


  /**
   * This method get a path to a file that is relative to the public static
   * folder and returns the absolute path to this file.
   */
  public function getStaticLink($relativePath) {
    return $this->getRoot() . '/' . $core->getTheme() . '/' . $relativePath;
  }


  public function extend($skelName) {
    $this->skeleton = $this->core->getSkeletonsDir() . '/' . 
        $skelName . '.php';
  }
  
  
  public function echoExtends() {
    global $tc;
    
    $echoBlocksPrev = $this->echoBlocks;
    $this->echoBlocks = true;
    
    require($this->skeleton);
    
    $this->echoBlocks = $echoBlocksPrev;
  }
  

  public function startBlock($name) {
    // TODO: Add cascading blocks support.
    array_push($this->blockStack, $name);
    ob_start();
  }


  public function endBlock() {
    // TODO: Add cascading blocks support.
    $name = array_pop($this->blockStack);
    $echo = '';
    
    if( $this->echoBlocks ) {
      if( key_exists($name, $this->blocks) )
        $echo = $this->blocks[$name];
      else
        $echo = ob_get_contents();
    } else { 
      $this->blocks[$name] = ob_get_contents();      
    }
    
    ob_end_clean();
    echo $echo;
  }







  /*

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

   public function addCss($filename) {
   $this->content['theme']['css'][] = $filename;
   }

   */

}
