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
    <h2>Dostupné testy pre vás</h2>

  <?php
  $sql = "SELECT * FROM testForStudents
        WHERE ((startDate IS NOT NULL AND deadlineDate IS NOT NULL) AND (CONCAT(startDate, ' ', timeDate) <= NOW() AND CONCAT(deadlineDate, ' ', deadlineTime) >= NOW()))
        OR (startDate IS NULL AND timeDate IS NULL AND deadlineDate IS NULL AND deadlineTime IS NULL)";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($tests as $row) {

      echo count($tests) . "<br>";

      $sql = "SELECT CONCAT(meno, ' ', priezvisko) AS name FROM myUserPanel WHERE id=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$row['teacher_id']]);
      $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

      $sql = "SELECT latexSubor FROM zadanieLatex WHERE id=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$row['latexSubor_id']]);
      $file = $stmt->fetch(PDO::FETCH_ASSOC);

      echo "startDate: " . $row['startDate'] . "<br>";
      echo "startTime: " . $row['timeDate'] . "<br>";
      echo "deadlineDate: " . $row['deadlineDate'] . "<br>";
      echo "deadlineTime: " . $row['deadlineTime'] . "<br>";
      echo "file: " . $file['latexSubor'] . "<br>";
      echo "teacher: " . $teacher['name'] . "<br>";
      echo "max_points: " . $row['maxPoint'] . "<br>";
      echo "<br>";
  }



  ?>

  </body>
</html>
