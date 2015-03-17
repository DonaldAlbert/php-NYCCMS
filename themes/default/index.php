<!DOCTYPE html>

<html>
    <head>
        <title>Demo default theme</title>
        <link rel="stylesheet" type="text/css" href="<?= '""';//$css; ?>" media="screen">
    </head>
    <body>
        <h1>Hello World<small>index</small></h1>

        <h2><?= $tc->getContent('value1'); ?></h2>

        <footer><?php include('partials/footer.php'); ?></footer>
    </body>
</html>