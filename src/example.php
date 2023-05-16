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
</head>
<body>
<ul>
    <li><a href="logedStudent.php" class="active"><img src = "photos/profile-circle-svgrepo-com.svg" alt="student"/></a></li>
    <li><a href="logout.php"><img src = "photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
</ul>
<?php echo "Hello " .$_SESSION["meno"]. " student"; ?>
<div>
    <?php
    $string = 'Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou $F(s)=\dfrac{6}{(5s+2)^2}e^{-4s}$';
    echo $string;
    ?>
</div>
<script type="text/x-mathjax-config">
        MathJax.Hub.Config({
        tex2jax: {
        inlineMath: [['$', '$']]
        }
        });
    </script>
</body>
</html>