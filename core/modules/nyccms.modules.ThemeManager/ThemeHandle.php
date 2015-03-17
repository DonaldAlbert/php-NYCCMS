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
  
  
  public function __construct(ThemeManager $core) {
    $this->core = $core;
  }
  
  
  private function getRoot() {
    if( !($this -> publicRoot) )
      $this -> publicRoot = Engine::getSettingsCore() -> get('public_root', '/');
    
    return $this -> publicRoot;
  }
  
  
  public function setContent($content) {
    $this -> content = $content;
  }
  
  
  public function getContent($key, $defalut = '') {
    if( key_exists($key, $this->content) )
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
  
  public function addCss($filename) {
      $this->content['theme']['css'][] = $filename;
  }

*/
 
}
