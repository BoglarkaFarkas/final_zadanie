<?php 
session_start();
if(!isset($_SESSION)){
    header("Location: ../../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Man Page</title>
    <!------------------------------------------CSS---------------------------------------->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link href="../../public/css/man.css" rel="stylesheet">
</head>
<body>
    <nav>
        <li><a href="../../loged.php"><img src = "../../photos/add-circle-svgrepo-com.svg" alt="student"/></a></li>
        <li><a href="stats.php"><img src = "../../photos/statistics.svg" alt="stats"/></a></li>
        <li><a href="teacherPdf.php"  class="active"><img src = "../../photos/guide-link-svgrepo-com.svg" alt="man"/></a></li>
        <li><a href="uploadForm.php"><img src = "../../photos/upload-svgrepo-com.svg" alt="upload"/></a></li>
        <li><a href="../../logout.php"><img src = "../../photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
    </nav>
    <div class="container my-5 shadow p-3 mb-5 bg-light rounded">
        <div class="row justify-content-center pt-5">
            <div class="col-lg-6 text-center">
                <h1>Manual</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div id="pdfContent">
                    Po prihlásení učiteľ vidí ovládací panel, v ktorom môže nastaviť body, dátum odovzdania a dátum spustenia
                    hociktorému súboru dostupnému na stránke. V ľavom hornom rohu stránky je menu, kde je možnosť presunúť sa
                    na podstránku obsahujúcu štatistiku. Štatistika obsahuje zoznam všetkých študentov s informáciami: id, meno,
                    priezvisko, počet dosiahnutých bodov, počet vygenerovaných úloh a počet úspešne vyriešených úloh. Tabuľku
                    študentov je možné si stiahnuť vo formáte csv po kliknutí na tlačidlo. Poslednou podstránkou je tento manuál,
                    ktorý je taktiež možné stiahnuť po kliknutí na tlačidlo vo formáte pdf.
                </div>
            </div>
        </div>
        <div class="row justify-content-center pt-3">
            <div class="col-lg-6 text-center pb-5">
                <form method="post" action="generate_pdf.php">
                    <input type="hidden" name="pdf_content" id="pdfContentInput">
                    <input type="submit" class="btn btn-primary" name="generate_pdf" value="Stiahnuť PDF">
                </form>
            </div>
        </div>
    </div>


    <script>
        window.onload = function() {
            var pdfContent = document.getElementById('pdfContent').innerHTML;
            document.getElementById('pdfContentInput').value = pdfContent;
        };
    </script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
