<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['files'])) { // Render
        foreach ($_POST['files'] as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $outputDir = "rendered_examples/$filename";

            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0777, true);
            }

            $output = exec("latex2html -no_navigation -dir $outputDir examples/$file");
            echo $output . "<br>";
            chmod($outputDir, 0777);
        }
    } else if (isset($_POST['del'])) { //Delete
        exec('rm -r rendered_examples/*');
    }
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test</title>
</head>
<body>
<a href="display_examples.php"><button>Display Examples</button></a>
<form method="post" action="render.php">
    <input type="submit" name="del" value="Delete all rendered examples">
    (This button deletes everything from dir rendered_examples)
</form><br><br>
<?php
$dir_path = 'examples';

$files = scandir($dir_path);

echo '<form action="render.php" method="post">';
foreach ($files as $file) {
    if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'tex') {
        echo '<label><input type="checkbox" name="files[]" value="' . $file . '"> ' . $file . '</label><br>';
    }
}
echo '<input type="submit" value="Process">';
echo '</form>';
?>
</body>
</html>
