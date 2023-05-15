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
require_once('private/config.php');

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Student</title>
    <link rel="stylesheet" href="public/css/style.css">
  </head>
  <body>
    <ul>
        <li><a href="logedStudent.php" class="active"><img src = "photos/profile-circle-svgrepo-com.svg" alt="student"/></a></li>
        <li><a href="logout.php"><img src = "photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
    </ul>
<?php echo "Hello " .$_SESSION["meno"]. " student"; ?>
  </body>
</html>
