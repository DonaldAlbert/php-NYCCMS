<?php
if( !defined('CMS_ROOT') )  exit();


// Step 1 - Core scripts inclusion/requisition
require_once('log_system.php');
require_once('modules_core.php');
require_once('engine.php');


// Step 2 - MoculesCore fire-up / Modules loading
$engine = \nyccms\core\Engine::getInstance();
$engine->initiate();
$module = $engine->getModulesCore()->getModule('nyccms.modules.Router');
$module->parseRequest();



// Step 3 - URL Parsing / Routing Actions



// Step 4 - Rendering Action 