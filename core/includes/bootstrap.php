<?php
if( !defined('CMS_ROOT') )  exit();


// Step 1 - Core scripts inclusion/requisition
require_once('logger.php');
require_once('engine.php');
require_once('modules_core.php');
require_once('settings_core.php');


// Step 2 - MoculesCore fire-up / Modules loading
use nyccms\core\Engine as Engine;
//Engine::initiate_production();
Engine::initiate_debug();
$core = Engine::getModulesCore();
$eventHorizon = $core->getModule('nyccms.modules.EventManager');
$router = $core->getModule('nyccms.modules.Router');



// Step 3 - URL Parsing / Routing Actions



// Step 4 - Rendering Action 