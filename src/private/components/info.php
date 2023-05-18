<?php
    session_start();
    if(!isset($_SESSION)){
        header("Location: ../../index.php");
        exit;
    }
    if($_SESSION['role']=='student'){
        header("Location: ../../logedStudent.php");
        exit;
    }
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats</title>
    <!------------------------------------------CSS---------------------------------------->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link href="../../public/css/table.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css">
    <!------------------------------------------JS---------------------------------------->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
</head>
<body>
  <div class="buttonsForBil">
    <button id="sk-button">SK</button>
    <button id="eng-button">ENG</button>
  </div>
  <nav>
      <li><a href="../../loged.php"><img src = "../../photos/add-circle-svgrepo-com.svg" alt="student"/></a></li>
      <li><a href="stats.php"><img src = "../../photos/statistics.svg" alt="logout"/></a></li>
      <li><a href="teacherPdf.php"><img src = "../../photos/guide-link-svgrepo-com.svg" alt="man"/></a></li>
      <li><a href="uploadForm.php"><img src = "../../photos/upload-svgrepo-com.svg" alt="upload"/></a></li>
      <li><a href="../../logout.php"><img src = "../../photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
  </nav>
  <section>
        <div class="container">
            <?php
                require_once("../classes/InfoTable.php");
                $table = new InfoTable($id);
                $htmlTable = $table->generateTable();
                echo $htmlTable;
            ?>
            <div>
                - = <span id="id61">rozpracované</span>
                <br>
                x = <span id="id62">nesprávne</span>
                <br>
                ok = <span id="id63">správne</span>
            </div>
            
            <div class="input-box">
               <button onclick="exportCSV()" id="exportBtn">Exportovať do CSV</button>
            </div>

        </div>
        
    </section>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
              searching: false,
                "paging": false, // Skryť stránkovanie
                "lengthChange": false,
                "info": false
            });
        } );
    </script>
        <script>
        document.getElementById("sk-button").addEventListener("click", function() {
        fetch('../../bilingual.json')
            .then(response => response.json())
            .then(data => {
            document.getElementById("id59").textContent = "Úloha";
            document.getElementById("id60").textContent = "Stav";
            document.getElementById("id58").textContent = "Body";
            document.getElementById("exportBtn").textContent = "Exportovať do CSV";
            document.getElementById("id61").textContent = "rozpracované";
            document.getElementById("id62").textContent = "nesprávne";
            document.getElementById("id63").textContent = "správne";
            });
        });

        document.getElementById("eng-button").addEventListener("click", function() {
        fetch('../../bilingual.json')
            .then(response => response.json())
            .then(data => {
            document.getElementById("id59").textContent = "Excercise";
            document.getElementById("id60").textContent = "State";
            document.getElementById("id58").textContent = "Points";
            document.getElementById("exportBtn").textContent = "Export to CSV";
            document.getElementById("id61").textContent = "in progress";
            document.getElementById("id62").textContent = "wrong";
            document.getElementById("id63").textContent = "correct";
            });
        });
    </script>
    <script src="../../public/js/exportCSV.js"></script>
</body>
</html>