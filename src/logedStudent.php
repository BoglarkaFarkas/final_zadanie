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

// If Continue or New Example buttons are pressed, the page redirects to example.php with the correct exampleID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['exampleID'])) {

    $exampleID = $_POST['exampleID'];
    unset($_POST['exampleID']);

    $_SESSION['exampleID'] = $exampleID;

    header('Location: example.php');
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
  <div class="buttonsForBil">
    <button id="sk-button">SK</button>
    <button id="eng-button">ENG</button>
  </div>
    <nav>
        <li><a href="logedStudent.php" class="active"><img src = "photos/profile-circle-svgrepo-com.svg" alt="student"/></a></li>
        <li><a href="private/components/studentPdf.php"><img src = "photos/guide-link-svgrepo-com.svg" alt="man"/></a></li>
        <li><a href="logout.php"><img src = "photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
    </nav>
    <?php  echo "<h3 id='id48'>User: " .$_SESSION["meno"]. " " .$_SESSION["priezvisko"]. " (student)</h3>";?>
    <h4 id="id47">Available tests for you</h4>


  <?php
  $sql = "SELECT DISTINCT file_name, start_date, deadline_date, points FROM examples WHERE (start_date IS NULL AND deadline_date IS NULL AND solvable = 1) OR (start_date IS NOT NULL AND deadline_date IS NOT NULL AND solvable = 1 AND NOW() BETWEEN start_date AND deadline_date);";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($tests as $row) {

      echo "<section> <p id='id49'>Name: </p>  " . $row['file_name'] . "<br>";

      if (empty($row['start_date']) && empty($row['deadline_date'])) {
          echo "There is no time limit" . "<br>";
      } else {
          echo "<p id='id50'> Start: </p>   " . $row['start_date'] . "<br>";
          echo "<p id='id51'> End: </p>  " . $row['deadline_date'] . "<br>";
      }

      echo "<p id='id52'> Points for a right answer:  </p> " . $row['points'] . "<br>";
      echo "<br>";

      checkSolvedExamples($pdo, $row['file_name'], $_SESSION['id']);

  }

  function checkSolvedExamples($pdo, $file_name, $student_id) {

      // Check if there is a started example from the test
      $sql = "SELECT ge.* FROM generatedExamples AS ge JOIN examples AS e ON ge.id_example = e.id WHERE ge.id_student = :id AND ge.status IS NULL AND e.file_name = :file_name;";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $student_id);
      $stmt->bindParam(':file_name', $file_name);
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

          echo "Every example solved from this test</section>";

      } else {
          if ($foundNull) {
              if (count($foundNull) > 0) {

                  $exampleID = $foundNull['id_example'];

                  $sql = "SELECT * FROM examples WHERE id = :id_example";
                  $stmt = $pdo->prepare($sql);
                  $stmt->bindParam(':id_example', $exampleID);
                  $stmt->execute();
                  $exampleToContinue = $stmt->fetch(PDO::FETCH_ASSOC);

                  echo "<p id='id54'> There is an already started example: </p> ";
                  echo $exampleToContinue['example_name'];

                  echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
                  echo '<input type="hidden" name="exampleID" value="' . $exampleID . '">';
                  echo '<input type="submit" id="continueBut" value="Continue" onclick="redirect()">';
                  echo '</form> </section>';

              }
          }
           else {

              echo "<p id='id53'> You can generate a new example </p>";

              // Pick a random row from the solvable ones
               $sql = "SELECT * FROM examples e WHERE e.file_name = :file_name AND e.id NOT IN ( SELECT id_example FROM generatedExamples WHERE id_student = :id_student ) ORDER BY RAND() LIMIT 1;";
               $stmt = $pdo->prepare($sql);
               $stmt->bindParam(':file_name', $file_name);
               $stmt->bindParam(':id_student', $student_id);
               $stmt->execute();
               $exampleID = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

               echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
               echo '<input type="hidden" name="exampleID" value="' . $exampleID . '">';
               echo '<input type="submit" id="continueBut2" value="New example" onclick="redirect()">';
               echo '</form></section>';
          }
      }
  }

  ?>
  <script>
      function redirect() {
          window.location.href = 'example.php';
      }
  </script>
  </section>
  <script>
    document.getElementById("sk-button").addEventListener("click", function() {
    fetch('bilingual.json')
        .then(response => response.json())
        .then(data => {
        document.getElementById("id47").textContent = data.sk.id47;
        var first = "<?php echo $_SESSION["meno"]; ?>";
        var second = "<?php echo $_SESSION["priezvisko"]; ?>";
        var elem = "<h3 id='id48'>Pouzivatel: " + first + " " + second + " (študent)</h3>";

        document.getElementById("id48").innerHTML = elem;
        document.getElementById("id49").textContent = data.sk.id49;
        document.getElementById("id50").textContent = data.sk.id50;
        document.getElementById("id51").textContent = data.sk.id51;
        document.getElementById("id52").textContent = data.sk.id52;
        document.getElementById("id54").textContent = data.sk.id54;
        document.getElementById("continueBut").value = "Pokračovať";
        document.getElementById("id53").textContent = data.sk.id53;
        document.getElementById("continueBut2").value = "Nový príklad";

        });
    });

    document.getElementById("eng-button").addEventListener("click", function() {
    fetch('bilingual.json')
        .then(response => response.json())
        .then(data => {
        document.getElementById("id47").textContent = data.eng.id47;
        var first = "<?php echo $_SESSION["meno"]; ?>";
        var second = "<?php echo $_SESSION["priezvisko"]; ?>";
        var elem = "<h3 id='id48'>User: " + first + " " + second + " (student)</h3>";

        document.getElementById("id48").innerHTML = elem;
        document.getElementById("id49").textContent = data.eng.id49;
        document.getElementById("id50").textContent = data.eng.id50;
        document.getElementById("id51").textContent = data.eng.id51;
        document.getElementById("id52").textContent = data.eng.id52;
        document.getElementById("id54").textContent = data.eng.id54;
        document.getElementById("continueBut").value = "Continue";
        document.getElementById("id53").textContent = data.eng.id53;
        document.getElementById("continueBut2").value = "New example";
        });
    });
</script>
  </body>
</html>
