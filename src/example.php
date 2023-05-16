<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$student='student';

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || strcmp($student,$_SESSION["role"]) != 0){
    header("Location: index.php");
    exit;
}
if(!isset($_SESSION['exampleID'])){
    header("Location: logedStudent.php");
    exit;
}
require_once('private/config.php');

$exampleID = $_SESSION['exampleID'];
unset($_SESSION['exampleID']);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Test</title>
    <script>
        MathJax = {
            tex: {
                inlineMath: [['$', '$'], ['\\(', '\\)']]
            },
            svg: {
                fontCache: 'global'
            }
        };
    </script>
    <script type="text/javascript" id="MathJax-script" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.2/es5/tex-svg.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.css" />
</head>
<body>
<ul>
    <li><a href="logedStudent.php" class="active"><img src = "photos/profile-circle-svgrepo-com.svg" alt="student"/></a></li>
    <li><a href="logout.php"><img src = "photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
</ul>
<?php echo "Hello " .$_SESSION["meno"]. " student"; ?>
<div>
    <?php


    $sql = "SELECT * FROM examples WHERE id = :id;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $exampleID);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<h2>" . $result['example_name'] . "</h2>";
    echo $result['example_body'];


    ?>
</div>
<script type="text/x-mathjax-config">
        MathJax.Hub.Config({
        tex2jax: {
        inlineMath: [['$', '$']]
        }
        });
</script>

<p>Solution: <span id="math-field"></span></p>
<p>In LaTeX: <span id="latex"></span></p>

<script>
    $(document).ready(function() {
        var mathFieldSpan = document.getElementById('math-field');
        var latexSpan = document.getElementById('latex');

        var MQ = MathQuill.getInterface(2);
        var mathField = MQ.MathField(mathFieldSpan, {
            spaceBehavesLikeTab: true,
            handlers: {
                edit: function() {
                    latexSpan.textContent = mathField.latex();
                }
            }
        });
    });
</script>
</body>
</html>