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

            //Add example and solution to table 'examples'
            $filepath = "examples/" . $filename . ".tex";

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
                            $line = str_replace('\\\\', '', $line);
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

                    $solution = str_replace(['\begin{equation*}', '\end{equation*}'], '', $solution);
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
                    $stmt = $pdo->prepare("INSERT INTO examples (file_name, example_name, example_body, solution, points, solvable) VALUES (:file_name, :example_name, :example_body, :solution, 0, 0)");
                    $stmt->bindParam(':file_name', $filename);
                    $stmt->bindParam(':example_name', $exampleName);
                    $stmt->bindParam(':example_body', $exampleBody);
                    $stmt->bindParam(':solution', $solution);
                    $stmt->execute();

                }
            }
        }
    }

    $sql = "SELECT DISTINCT file_name FROM examples";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $start_date = $_POST['mydate'] . ' ' . $_POST['mytime'];
        $deadline_date = $_POST['enddate'] . ' ' . $_POST['endtime'];
        $points = $_POST['maxnumber'];

        $query = "UPDATE examples SET start_date = :start_date, deadline_date = :deadline_date, points = :points, solvable = 1 WHERE file_name = :file_name";
        $statement = $pdo->prepare($query);

        $statement->bindParam(':file_name', $_POST["my-select"]);
        $statement->bindParam(':start_date', $start_date);
        $statement->bindParam(':deadline_date', $deadline_date);
        $statement->bindParam(':points', $points);

        $statement->execute();

        $_POST = null;
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
  <div class="buttonsForBil">
    <button id="sk-button">SK</button>
    <button id="eng-button">ENG</button>
  </div>
  <body>
    <nav>
        <li><a href="loged.php" class="active"><img src = "photos/add-circle-svgrepo-com.svg" alt="student"/></a></li>
        <li><a href="private/components/stats.php"><img src = "photos/statistics.svg" alt="logout"/></a></li>
        <li><a href="logout.php"><img src = "photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
    </nav>
<?php  echo "<h3 id='id31'>User: " .$_SESSION["meno"]. " " .$_SESSION["priezvisko"]. "(učiteľ)</h3>";?>
<br>
<br>
<section>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <div class="mb-3">
  <label for="mydate" class="form-label">
    <span id="id24">Start date</span>:
      <input type="date" class="form-control" name="mydate" value="" id="mydate" required>
  </label>

  <label for="mytime" class="form-label">
    <span id="id25">Start time</span>:
      <input type="time" class="form-control" name="mytime" value="" id="mytime" required>
  </label>
  <br>
  <label for="maxnumber" class="form-label">
    <span id="id26">Max points</span>:
      <input type="number" class="form-control" name="maxnumber" value="" id="maxnumber" required>
  </label>

  <label for="my-select" class="form-label">
    <span id="id27">File</span>:
  <?php
  $select_options = "";
  foreach ($results as $option) {
    $file_name = $option['file_name'];
    $select_options .= "<option value='$file_name'>$file_name</option>";
  }
  $select_element = "<select name='my-select' id='my-select' class='form-control'>$select_options</select>";
  echo $select_element;
?>
</label>
<br>
<label for="enddate" class="form-label">
    <span id="id28">Deadline date</span>:
    <input type="date" class="form-control" name="enddate" value="" id="enddate" required>
</label>

<label for="endtime" class="form-label">
    <span id="id29">Deadline time</span>:
    <input type="time" class="form-control" name="endtime" value="" id="endtime" required>
</label>
<br><br>
  <button type="submit" id="id30">Submit</button>
  </div>
</form>
</section>
<script>
    document.getElementById("sk-button").addEventListener("click", function() {
    fetch('bilingual.json')
        .then(response => response.json())
        .then(data => {
        document.getElementById("id24").textContent = data.sk.id24;
        document.getElementById("id25").textContent = data.sk.id25;
        document.getElementById("id26").textContent = data.sk.id26;
        document.getElementById("id27").textContent = data.sk.id27;
        document.getElementById("id28").textContent = data.sk.id28;
        document.getElementById("id29").textContent = data.sk.id29;
        document.getElementById("id30").textContent = data.sk.id30;
        var first = "<?php echo $_SESSION["meno"]; ?>";
        var second = "<?php echo $_SESSION["priezvisko"]; ?>";
        var elem = "<h3 id='id31'>Pouzivatel: " + first + " " + second + " (učiteľ)</h3>";

        document.getElementById("id31").innerHTML = elem;
        });
    });

    document.getElementById("eng-button").addEventListener("click", function() {
    fetch('bilingual.json')
        .then(response => response.json())
        .then(data => {
        document.getElementById("id24").textContent = data.eng.id24;
        document.getElementById("id25").textContent = data.eng.id25;
        document.getElementById("id26").textContent = data.eng.id26;
        document.getElementById("id27").textContent = data.eng.id27;
        document.getElementById("id28").textContent = data.eng.id28;
        document.getElementById("id29").textContent = data.eng.id29;
        document.getElementById("id30").textContent = data.eng.id30;
        var first = "<?php echo $_SESSION["meno"]; ?>";
        var second = "<?php echo $_SESSION["priezvisko"]; ?>";
        var elem = "<h3 id='id31'>User: " + first + " " + second + " (teacher)</h3>";

        document.getElementById("id31").innerHTML = elem;
        });
    });
</script>
  </body>
</html>
