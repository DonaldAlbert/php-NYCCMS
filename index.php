<?php
/**
 * Created by PhpStorm.
 * User: Ioannis
 * Date: 19-Jan-15
 * Time: 8:56 PM
 */


$start_time = microtime(true);




define('CMS_ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once('includes/bootstrap.php');
//boot();


//var_dump($url->GetUrlComponents(True));
//echo $url->build_Path(array('action'=>"create",'type'=>"page"));
//echo "<br>".$url->build_Link("mylink", "My Link Text", array('action'=>"create",'type'=>"page") );

//    $url= new URL();
//    $url->writeURL(array('action'=>"add",'type'=>null,'id'=>null));
//    Redirection::Redirect($url);


 // Template engine test code
 require_once('includes/classes/theme_engine.php');
 $my_content = [
   'h2' => 'some more text',
 ];
 $te = ThemeEngine::getInstance();
 $te->addCss('css/common.css');
$te->renderTheme('page1-blocks', $my_content);
//$te->renderTheme('page1-blocks', $my_content);


// // Modules Core test code
// include('includes/classes/modules_core.php');
// $modMgr = ModulesCore::getInstance();
// $modMgr->loadModule('core.testing.TemplateEngine');
// $modMgr->loadModule('core.testing.TemplateEngine'); // Ignored, cause it is already loaded.
// $modMgr->loadModule('Module_Routing_Engine');
// $modMgr->loadInterfaces();
// $modMgr->dumpIfs();
// 
// $tplMod = $modMgr->getModule('core.testing.TemplateEngine');
// $tplMod->templateMethod1('value1');
// $tplMod->templateMethod2('value1');
// 
// $tplMod = $modMgr->getModule('Module_Routing_Engine');
// $tplMod->routingMethod1('value1');



$duration = round((microtime(true)-$start_time)*1000, 3);
echo "\n<br /><div style=\"text-align: center; font-family: monospace; color: dimgray;\">Overall time: <b>$duration ms</b></div>";
?>
