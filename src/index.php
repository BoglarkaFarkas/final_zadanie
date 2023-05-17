<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  require_once('private/config.php');
  try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $sql = "SELECT * FROM users WHERE email = :email";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(":email", $_POST["email"], PDO::PARAM_STR);
      if ($stmt->execute()) {
          if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            $hashed_password = $row["password"];
              if (password_verify($_POST['password'], $hashed_password)) {
                  session_start();
                  $_SESSION["loggedin"] = true;
                  $_SESSION["priezvisko"] = $row['surname'];
                  $_SESSION["meno"] = $row['name'];
                  $_SESSION["email"] = $row['email'];
                  $_SESSION['id'] = $row['id'];
                  $_SESSION['role'] = $row['role'];
                  $student='student';
                  if($row['role']==$student){
                      header("location: logedStudent.php");
                  }else{
                      header("location: loged.php");
                  }
              }else {
                echo '<div class="alert alert-danger" id="id5">Nespravny email alebo heslo.</div>';
              }

          }else{
            echo '<div class="alert alert-danger" id="id5">Nespravny email alebo heslo.</div>';
          }
        }else{
          echo '<div class="alert alert-danger" id="id6">Ups. Nieco sa pokazilo.</div>';
        }
        unset($stmt);
        unset($pdo);
    }

  } catch (PDOException $e) {
      echo $e->getMessage();
  }

 ?>
<!doctype html>
<html lang="sk">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login</title>
  <!------------------------------------------JS---------------------------------------->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <!------------------------------------------CSS---------------------------------------->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="public/css/style.css">



</head>
<body>
  <div class="buttonsForBil">
    <button id="sk-button">SK</button>
    <button id="eng-button">ENG</button>
  </div>
  <section>
    <header>
      <hgroup>
          <h1 id="id1">Login:</h1>
        </hgroup>
    </header>
    <main>
      <div class="registme">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="mb-3">
            <label for="email" class="form-label">
                <span id="id2">Email</span>:
                <input type="text" class="form-control" name="email" value="" id="email" required>
            </label>
            </div>
            <div class="mb-3">
            <label for="password" class="form-label">
                <span id="id3">Password</span>:
                <input type="password" class="form-control" name="password" value="" id="password" required>
            </label>
            </div>
            <button type="submit" id="id4">Prihlasit sa</button>
        </form>
        <p id="id7">Nemáte účet? <a href="private/components/registration.php" id='id8'>Zaregistrujte sa</a></p>
      </div>

    </main>
  </section>
  <script>

document.getElementById("sk-button").addEventListener("click", function() {
  fetch('bilingual.json')
    .then(response => response.json())
    .then(data => {
      document.getElementById("id1").textContent = data.sk.id1;
      document.getElementById("id2").textContent = data.sk.id2;
      document.getElementById("id3").textContent = data.sk.id3;
      document.getElementById("id4").textContent = data.sk.id4;
      document.getElementById("id5").textContent = data.sk.id5;
      document.getElementById("id6").textContent = data.sk.id6;
      document.getElementById("id7").innerHTML = data.sk.id7 + ' <a href="private/components/registration.php" id="id8">Zaregistrujte sa</a>';
     // document.getElementById("id8").innerHTML = ' <a href="private/components/registration.php" id="id8">Zaregistrujte sa</a>';
    });
});

document.getElementById("eng-button").addEventListener("click", function() {
  fetch('bilingual.json')
    .then(response => response.json())
    .then(data => {
      document.getElementById("id1").textContent = data.eng.id1;
      document.getElementById("id2").textContent = data.eng.id2;
      document.getElementById("id3").textContent = data.eng.id3;
      document.getElementById("id4").textContent = data.eng.id4;
      document.getElementById("id5").textContent = data.eng.id5;
      document.getElementById("id6").textContent = data.eng.id6;
      document.getElementById("id7").innerHTML = data.eng.id7 + ' <a href="private/components/registration.php" id="id8">Sign up</a>';
      //document.getElementById("id8").textContent = data.eng.id8;
    });
});
</script>
 </body>
 </html>
