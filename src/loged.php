<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$ucitel='ucitel';
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || strcmp($ucitel,$_SESSION["role"]) != 0){
    header("Location: index.php");
    exit;
}
require_once('private/config.php');
try {
    //Checking examples folder for new content
    $files = scandir('examples');

    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'tex') {
            $filename = pathinfo($file, PATHINFO_FILENAME);

            // Check if file with the same name already exists in the database
            $sql = "SELECT COUNT(*) as count FROM zadanieLatex WHERE latexSubor = :filename";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':filename', $filename);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $result['count'];

            if ($count > 0) {
                continue;
            }

            $sql = "INSERT INTO zadanieLatex (latexSubor) VALUES (:filename)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':filename', $filename);
            $stmt->execute();
        }
    }

    $sql = "SELECT * FROM zadanieLatex";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $startDate=$_POST['mydate'];
        $timeDate=$_POST['mytime'];
        $deadlineDate=$_POST['enddate'];
        $deadlineTime=$_POST['endtime'];

        //Add example and solution to table 'examples'
        $filepath = "examples/" . $_POST['my-select'] . ".tex";

        $file = fopen($filepath, 'r');
        $texContent = fread($file, filesize($filepath));
        fclose($file);

        $exampleNames = [];
        $taskBodies = [];
        $solutions = [];

        $lines = explode("\n", $texContent);
        $totalLines = count($lines);

        for ($i = 0; $i < $totalLines; $i++) {
            // Check for example name
            if (strpos($lines[$i], '\section*{') !== false) {
                $exampleName = trim(str_replace(['\section*{', '}'], '', $lines[$i]));
                $exampleNames[] = $exampleName;
            }

            // Check for task body
            if (strpos($lines[$i], '\begin{task}') !== false) {
                $taskBody = '';
                $i++;

                while ($i < $totalLines && strpos($lines[$i], '\end{task}') === false) {
                    $line = trim($lines[$i]);
                    if (!empty($line)) {
                        $line = str_replace(['\begin{equation*}', '\end{equation*}'], '$', $line);
                        $taskBody .= $line . "\n";
                    }
                    $i++;
                }

                $taskBodies[] = trim($taskBody);
            }

            // Check for solution
            if (strpos($lines[$i], '\begin{solution}') !== false) {
                $solution = '';
                $i++;

                while ($i < $totalLines && strpos($lines[$i], '\end{solution}') === false) {
                    $line = trim($lines[$i]);
                    if (!empty($line)) {
                        $solution .= $line . "\n";
                    }
                    $i++;
                }

                $solution = str_replace(['\begin{equation*}', '\end{equation*}'], '$', $solution);
                $solutions[] = trim($solution);
            }
        }

        // Insert examples from the files to the database
        for ($i = 0; $i < count($exampleNames); $i++) {

            $exampleName = $exampleNames[$i];
            $exampleBody = $taskBodies[$i];
            $solution = $solutions[$i];

            // Check if example with the same name already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM examples WHERE example_name = :example_name");
            $stmt->bindParam(':example_name', $exampleName);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                $stmt = $pdo->prepare("INSERT INTO examples (file_name, example_name, example_body, solution) VALUES (:file_name, :example_name, :example_body, :solution)");
                $stmt->bindParam(':file_name', $_POST['my-select']);
                $stmt->bindParam(':example_name', $exampleName);
                $stmt->bindParam(':example_body', $exampleBody);
                $stmt->bindParam(':solution', $solution);
                $stmt->execute();

            }
        }


        $sql1 = "SELECT id FROM zadanieLatex WHERE latexSubor = :latexSubor";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->bindParam(":latexSubor", $_POST["my-select"], PDO::PARAM_STR);
        $stmt1->execute();
        $row2 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $latexSubor_id=$row2['id'];

        $teacher_id= $_SESSION["id"];
        $maxPoint=$_POST['maxnumber'];

        $sql2 = "INSERT INTO testForStudents (startDate, timeDate, deadlineDate, deadlineTime, latexSubor_id,teacher_id, maxPoint) VALUES (?,?,?,?,?,?,?)";
        $stmt2 = $pdo->prepare($sql2);
        $success = $stmt2->execute([$startDate, $timeDate,$deadlineDate, $deadlineTime,$latexSubor_id,$teacher_id,$maxPoint]);
    }


  }catch (PDOException $e) {
      echo $e->getMessage();
  }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </head>
  </head>
  <body>
    <ul>
        <li><a href="loged.php" class="active"><img src = "photos/add-circle-svgrepo-com.svg" alt="student"/></a></li>
        <li><a href="private/components/stats.php"><img src = "photos/statistics.svg" alt="logout"/></a></li>
        <li><a href="logout.php"><img src = "photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
    </ul>
<?php  echo "<h3>User: " .$_SESSION["meno"]. " " .$_SESSION["priezvisko"]. " (učiteľ)</h3>";?>
<br>
<br>
<article>


<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <div class="mb-3">
  <label for="mydate" class="form-label">
      Start date:
      <input type="date" class="form-control" name="mydate" value="" id="mydate" required>
  </label>

  <label for="mytime" class="form-label">
      Start time:
      <input type="time" class="form-control" name="mytime" value="" id="mytime" required>
  </label>
  <br>
  <label for="maxnumber" class="form-label">
      Max points:
      <input type="number" class="form-control" name="maxnumber" value="" id="maxnumber" required>
  </label>

  <label for="my-select" class="form-label">
    File:
  <?php
  $select_options = "";
  foreach ($results as $option) {
    $file_name = $option['latexSubor'];
    $select_options .= "<option value='$file_name'>$file_name</option>";
  }
  $select_element = "<select name='my-select' id='my-select' class='form-control'>$select_options</select>";
  echo $select_element;
?>
</label>
<br>
<label for="enddate" class="form-label">
    Deadline date:
    <input type="date" class="form-control" name="enddate" value="" id="enddate" required>
</label>

<label for="endtime" class="form-label">
    Deadline time:
    <input type="time" class="form-control" name="endtime" value="" id="endtime" required>
</label>
<br><br>
  <button type="submit">Submit</button>
  </div>
</form>
</article>
  </body>
</html>
