<?php
/**
 * Created by PhpStorm.
 * User: GRE-ENG
 * Date: 1/19/2015
 * Time: 8:41 PM
 */
require_once 'includes/bootstrap.php';


$url_manager = new URL();
print_r($url_manager->GetUrlComponents());

$param = [
    'action'    => 'autodistruct',
    'type'      => 'total',
    'id'        => 'terminator',
];

$content = '<br />';
$content.= $url_manager->build_link('1','Auto Destruction', $param);
$content.= '<br />';
$content.= $url_manager->build_Path($param);


include 'themes/theme.php';