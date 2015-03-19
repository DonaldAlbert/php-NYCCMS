<!DOCTYPE html>

<html>
    <head>
        <title>Demo default theme</title>
        <link rel="stylesheet" type="text/css" href="<?= '""';//$css; ?>" media="screen">
    </head>
    <body>
        <h1>Hello World<small>index</small></h1>

        <h2><?= $tc->getContent('value1'); ?></h2>
        
        <p><?php $tc->startBlock('block01');?>Defualt block01<?php $tc->endBlock(); ?></p>

        <footer>Inline html footer</footer>
    </body>
</html>