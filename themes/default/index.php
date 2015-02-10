<!DOCTYPE html>

<html>
    <head>
        <title>Demo default theme</title>
<?php
    foreach( $theme_content['theme']['css'] as $css ) {
        $css = $theme_content['theme']['root'] . '/' . $css;
?>
        <link rel="stylesheet" type="text/css" href="<?= $css; ?>" media="screen">
<?php
    }
?>
    </head>
    <body>
        <h1>Hello World<small>just testing</small></h1>

        <h2><?= $theme_content['content']['h2']; ?></h2>

        <footer><?php include('partials/footer.php'); ?></footer>
    </body>
</html>