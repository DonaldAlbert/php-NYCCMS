<?php
/**
 * Created by PhpStorm.
 * User: Ioannis
 * Date: 19-Jan-15
 * Time: 8:56 PM
 */


$start_time = microtime(true);



require_once("includes/bootstrap.php");
boot();


//var_dump($url->GetUrlComponents(True));
//echo $url->build_Path(array('action'=>"create",'type'=>"page"));
//echo "<br>".$url->build_Link("mylink", "My Link Text", array('action'=>"create",'type'=>"page") );

    $url= new URL();
    $url->writeURL(array('action'=>"add",'type'=>null,'id'=>null));
Redirection::Redirect($url);



$duration = round((microtime(true)-$start_time)*1000, 3);
echo "\n<br /><div style=\"text-align: center; font-family: monospace; color: dimgray;\">Overall time: <b>$duration ms</b></div>";
?>