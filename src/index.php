<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('private/config.php');
try {
    //$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $sql = "SELECT * FROM myUserPanel WHERE email = :email";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(":email", $_POST["email"], PDO::PARAM_STR);
      if ($stmt->execute()) {
          if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            $hashed_password = $row["heslo"];
              if (password_verify($_POST['heslo'], $hashed_password)) {
                  session_start();
                  $_SESSION["loggedin"] = true;
                  $_SESSION["priezvisko"] = $row['priezvisko'];
                  $_SESSION["meno"] = $row['meno'];
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
                echo '<script>alert("Nespravny email alebo heslo!");</script>';
              }

          }else{
            echo '<script>alert("Nespravny email alebo heslo!");</script>';
          }
        }else{
          echo '<script>alert("Ups. Nieco sa pokazilo!");</script>';
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
     <meta name="viewport"
           content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Login</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
     <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
     <link rel="stylesheet" href="public/css/style.css">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
     </head>
 <body>
<article>
 <header>
     <hgroup>
         <h1>Login</h1>
       </hgroup>
   </header>
   <main>

   <div class="registme">
     <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
         <div class="mb-3">
         <label for="email" class="form-label">
             Email:
             <input type="text" class="form-control" name="email" value="" id="email" required>
         </label>
         </div>
         <div class="mb-3">
         <label for="heslo" class="form-label">
             Password:
             <input type="password" class="form-control" name="heslo" value="" id="heslo" required>
         </label>
         </div>
         <button type="submit">Prihlasit sa</button>
     </form>

   </div>

 </main>
 </article>
 </body>
 </html>
