<?php
/**
 * Created by PhpStorm.
 * User: GRE-ENG
 * Date: 1/22/2015
 * Time: 8:33 PM
 */

class URLRouter {
    private $routingFile;
    private $routingTable;
    private $controllerDir;
    private $controllerPrefix;

    private function loadRoutingFile() {
        if( file_exists($this->routingFile) )
        {
            $this->routingTable = json_decode(readfile($this->routingFile));
        }
    }

    private function setControllerDir(string $dirName)
    {

    }

    public function setRoutingFile(string $fileName) {
        $this->routingFile = $fileName;
    }

    public function isRouted(string $action) {
        return array_key_exists($action, $this->routingTable);
    }

    public function route($action) {
        $route = $this->routingTable[$action];
        if( array_key_exists('script', $route) ) {
            include_once();
        }
    }
} 