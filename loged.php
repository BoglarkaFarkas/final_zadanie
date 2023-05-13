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
require_once('config.php');
try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM zadanieLatex";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </head>
  </head>
  <body>
    <ul>
        <li><a href="loged.php" class="active"><img src = "photos/add-circle-svgrepo-com.svg" alt="student"/></a></li>
        <li><a href="loged.php"><img src = "photos/statistics.svg" alt="logout"/></a></li>
        <li><a href="logout.php"><img src = "photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
    </ul>
<?php  echo "Hello " .$_SESSION["meno"]. " ucitel";?>
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
