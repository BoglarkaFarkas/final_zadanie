<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
    if(!isset($_SESSION)){
        header("Location: ../../index.php");
        exit;
    }
    if($_SESSION['role']=='student'){
        header("Location: ../../logedStudent.php");
        exit;
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
      <li><a href="stats.php"  class="active"><img src = "../../photos/statistics.svg" alt="logout"/></a></li>
      <li><a href="teacherPdf.php"><img src = "../../photos/guide-link-svgrepo-com.svg" alt="man"/></a></li>
      <li><a href="uploadForm.php"><img src = "../../photos/upload-svgrepo-com.svg" alt="upload"/></a></li>
      <li><a href="../../logout.php"><img src = "../../photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
  </nav>
  <h3 class="py-2" id="id20">Zoznam študentov</h3>
    <section>
        <div class="container">
            <?php
                require_once("../classes/Table.php");
                $table = new Table();
                $htmlTable = $table->generateTable();
                echo $htmlTable;
            ?>
            <div class="input-box">
               <button onclick="exportCSV()" id="exportBtn">Exportovať do CSV</button>
            </div>

        </div>
        
    </section>
    <!------------------------------------------JS---------------------------------------->
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
              searching: false,
              "columnDefs": [
                { "orderData": [5, 2], "targets": 5 }, 
                { "orderData": [2], "targets": 2 } 
                ],
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
            document.getElementById("id20").textContent = "Zoznam študentov";
            document.getElementById("exportBtn").textContent = "Exportovať do CSV";
            document.getElementById("id23").textContent = "ID";
            document.getElementById("id21").textContent = "Meno";
            document.getElementById("id22").textContent = "Priezvisko";
            document.getElementById("id56").textContent = "Vygenerované príklady";
            document.getElementById("id57").textContent = "Odovzdané";
            document.getElementById("id58").textContent = "Body";
            });
        });

        document.getElementById("eng-button").addEventListener("click", function() {
        fetch('../../bilingual.json')
            .then(response => response.json())
            .then(data => {
            document.getElementById("id20").textContent = "List of students";
            document.getElementById("exportBtn").textContent = "Export to CSV";
            document.getElementById("id23").textContent = "ID";
            document.getElementById("id21").textContent = "Name";
            document.getElementById("id22").textContent = "Surname";
            document.getElementById("id56").textContent = "Generated excercises";
            document.getElementById("id57").textContent = "Submitted";
            document.getElementById("id58").textContent = "Points";
            });
        });
    </script>
    <script src="../../public/js/exportCSV.js"></script>

</body>
