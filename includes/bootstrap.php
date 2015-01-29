<?php
/**
 * Date: 19-Jan-15
 * Time: 8:50 PM
 */

global $content;
/**
 *Bootloader function
 */
function boot ()
{
//    include("theme_engine.php");
//    include("url_class.php");
//    include("pdo_class.php");

    $includes = glob ("includes/*");
    foreach ($includes as $file )
    {
       if (is_readable($file) && !is_uploaded_file($file))
       {
           include_once($file);
       }
    }

    $modMgr = ModulesCore::getInstance();
    $modMgr->loadInterfaces();
    $modMgr->loadModule('Module_Template_Engine');
    $modMgr->loadModule('Module_Template_Engine'); // Ignored, cause it is already loaded.
    $modMgr->loadModule('Module_Routing_Engine');
    
    $tplMod = $modMgr->getModule('Module_Template_Engine');
    $tplMod->templateMethod1('value1');
    
    $tplMod = $modMgr->getModule('Module_Routing_Engine');
    $tplMod->routingMethod1('value1');
    
    include("themes/theme.php");
}
?>