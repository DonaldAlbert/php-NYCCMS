<!DOCTYPE html>

<html>
<head>
    <title>Demo default theme</title>
    <?php
    foreach( $tc->content['theme']['css'] as $css ) {
        $css = $tc->content['theme']['root'] . '/' . $css;
        ?>
        <link rel="stylesheet" type="text/css" href="<?= $css; ?>" media="screen">
    <?php
    }
    ?>
</head>
<body>
<h1>Hello World<small>page1-blocks</small></h1>

<h2><?= $tc->content['content']['h2']; ?></h2>

<div style="border: 1px solid red;"><?php $tc->startBlock('block1'); ?>
    this is the default context of <code>block1</code>.
<?php $tc->endBlock();?></div><br />


<div style="border: 1px solid blue;"><?php $tc->startBlock('block2'); ?>
    this is the default context of <code>block2</code>.
<?php $tc->endBlock();?></div></div><br />

<footer><?php include('partials/footer.php'); ?></footer>
</body>
</html>