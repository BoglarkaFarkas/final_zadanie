<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$dir = '/var/www/site240.webte.fei.stuba.sk/zadZ/rendered_examples/';
$files = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($iterator as $file) {
    if ($file->isFile() && preg_match('/node\d+\.html$/', $file->getFilename())) {
        $files[] = $file->getPathname();
    }
}

foreach ($files as $file) {

    $html = file_get_contents($file);

    if (preg_match('/<TITLE>\s*About this document/', $html)) {
        continue;  // skip if it is an 'About this document' file
    }

    preg_match('/<TITLE>(.*?)<\/TITLE>/', $html, $matches);

    if (!empty($matches[1])) {
        $name = $matches[1];
        $relative_link = str_replace('/var/www/site240.webte.fei.stuba.sk/zadZ/', ' ', $file);
        echo '<a href="' . htmlspecialchars($relative_link) . '">' . htmlspecialchars($name) . '</a><br>';
    }
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Display examples</title>
</head>
<body>
<a href="render.php"><button>Back to selection</button></a>
</body>
</html>
