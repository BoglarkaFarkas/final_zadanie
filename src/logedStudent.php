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
  $sql = "SELECT DISTINCT file_name, start_date, deadline_date, points FROM examples WHERE (start_date IS NULL AND deadline_date IS NULL AND solvable = 1) OR (start_date IS NOT NULL AND deadline_date IS NOT NULL AND solvable = 1 AND NOW() BETWEEN start_date AND deadline_date);";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($tests as $row) {

      echo "Name:   " . $row['file_name'] . "<br>";

      if (empty($row['start_date']) && empty($row['deadline_date'])) {
          echo "There is no time limit" . "<br>";
      } else {
          echo "Start:   " . $row['start_date'] . "<br>";
          echo "End:   " . $row['deadline_date'] . "<br>";
      }

      echo "Points for a right answer:   " . $row['points'] . "<br>";
      echo "<br>";

      checkSolvedExamples($pdo, $row['file_name'], $_SESSION['id']);

  }

  function checkSolvedExamples($pdo, $file_name, $student_id) {

      // Check if there is a started example from the test
      $sql = "SELECT * FROM generatedExamples WHERE id_student = :id AND status IS NULL;";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $student_id);
      $stmt->execute();
      $foundNull = $stmt->fetch(PDO::FETCH_ASSOC);

      // Count the number of examples by file_name in the examples table
      $sql = "SELECT COUNT(*) AS example_count FROM examples WHERE file_name = :file_name";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':file_name', $file_name);
      $stmt->execute();
      $example_count = $stmt->fetch(PDO::FETCH_ASSOC)['example_count'];

      // Count the number of id_example values with the same file_name in the generatedExamples table
      $sql = "SELECT COUNT(*) AS solved_example_count FROM generatedExamples JOIN examples ON generatedExamples.id_example = examples.id WHERE examples.file_name = :file_name AND generatedExamples.id_student = :student_id AND generatedExamples.status IS NOT NULL";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':file_name', $file_name);
      $stmt->bindParam(':student_id', $student_id);
      $stmt->execute();
      $solved_example_count = $stmt->fetch(PDO::FETCH_ASSOC)['solved_example_count'];

      if ($example_count == $solved_example_count) {

          echo "Every example solved from this test";

      } else {
          if ($foundNull) {
              if (count($foundNull) > 0) {

                  $id_example = $foundNull['id_example'];

                  $sql = "SELECT * FROM examples WHERE id = :id_example";
                  $stmt = $pdo->prepare($sql);
                  $stmt->bindParam(':id_example', $id_example);
                  $stmt->execute();
                  $exampleToContinue = $stmt->fetch(PDO::FETCH_ASSOC);

                  echo "There is an already started example<br>";
                  echo $exampleToContinue['example_name'];

                  $_SESSION['exampleID'] = $id_example;

                  echo '<form method="post" action="example.php">';
                  echo '<input type="submit" value="Continue" onclick="redirect()">';
                  echo '</form>';

              }
          }
           else {

              echo "You can generate a new example";

              // Pick a random row from the solvable ones
               $sql = "SELECT * FROM examples e WHERE e.file_name = :file_name AND e.id NOT IN ( SELECT id_example FROM generatedExamples WHERE id_student = :id_student ) ORDER BY RAND() LIMIT 1;";
               $stmt = $pdo->prepare($sql);
               $stmt->bindParam(':file_name', $file_name);
               $stmt->bindParam(':id_student', $student_id);
               $stmt->execute();
               $randomExample = $stmt->fetch(PDO::FETCH_ASSOC);


               $_SESSION['exampleID'] = $randomExample['id'];

               echo '<form method="post" action="example.php">';
               echo '<input type="submit" value="New example" onclick="redirect()">';
               echo '</form>';
          }
      }
  }

  ?>
  <script>
      function redirect() {
          window.location.href = 'example.php';
      }
  </script>
  </body>
</html>
