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
        <h1>Hello World<small>index</small></h1>

        <h2><?= $tc->content['content']['h2']; ?></h2>

        <footer><?php include('partials/footer.php'); ?></footer>
    </body>
</html>