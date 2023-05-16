<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
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
    <section>
        <div class="container">
            <h3 class="py-2">Zoznam študentov</h3>
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
              searching: false  
            });
        } );
    </script>
    <script src="../../public/js/exportCSV.js"></script>
</body>