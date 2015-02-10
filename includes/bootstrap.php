<?php
/**
 * Date: 19-Jan-15
 * Time: 8:50 PM
 */

if( !defined('CMS_ROOT') ) die();

global $content;
/**
 *Bootloader function
 */
function boot ()
{
    global $content;
//    $content = null;
    fileloader("includes/classes/*");
    fileloader("includes/misc/*");
    $url = new URL();
    Router::execute_Module($url, null);
    include("themes/theme.php");
}

    /**
     * Functiion to load files from given path
     * @param $path
     */
    function fileloader($path)
{
    $includes = glob ("$path");
    foreach ($includes as $file )
    {
        if (is_readable($file) && !is_uploaded_file($file))
        {
            include_once($file);
        }
    }
<<<<<<< HEAD
=======

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
>>>>>>> my_modifications
}
?>