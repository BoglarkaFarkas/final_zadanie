<?php
session_start();
if(!isset($_SESSION)){
    header("Location: ../../index.php");
    exit;
}
if($_SESSION['role']=='teacher'){
    header("Location: ../../loged.php");
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
<div class="buttonsForBil">
    <button id="sk-button">SK</button>
    <button id="eng-button">ENG</button>
  </div>
    <nav>
        <li><a href="../../logedStudent.php"><img src = "../../photos/add-circle-svgrepo-com.svg" alt="student"/></a></li>
        <li><a href="studentPdf.php"  class="active"><img src = "../../photos/guide-link-svgrepo-com.svg" alt="man"/></a></li>
        <li><a href="../../logout.php"><img src = "../../photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
    </nav>
    <section>
        <div class="row justify-content-center pt-5">
            <div class="col-lg-6 text-center">
                <h1 id="id31">Manual</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div id="pdfContent">
                    Po prihlásení vidí študnet dostupné úlohy, ktoré môže vypracovať a na ďalšej podstránke je tento manuál,
                    ktorý je taktiež možné stiahnuť po kliknutí na tlačidlo vo formáte pdf.
                </div>
            </div>
        </div>
        <div class="row justify-content-center pt-3">
            <div class="col-lg-6 text-center pb-5">
                <form method="post" action="generate_pdf.php">
                    <input type="hidden" name="pdf_content" id="pdfContentInput">
                    <input type="submit" class="myButtonForm2" id="id34" name="generate_pdf" value="Stiahnuť PDF">
                </form>
            </div>
        </div>
  </section>

    <script>
        window.onload = function() {
            var pdfContent = document.getElementById('pdfContent').innerHTML;
            document.getElementById('pdfContentInput').value = pdfContent;
        };
    </script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     <script>

        document.getElementById("sk-button").addEventListener("click", function() {
        fetch('../../bilingual.json')
            .then(response => response.json())
            .then(data => {
            document.getElementById("id31").textContent = data.sk.id31;
            document.getElementById("pdfContent").innerHTML = "Po prihlásení vidí študnet dostupné úlohy, ktoré môže vypracovať a na ďalšej podstránke je tento manuál, ktorý je taktiež možné stiahnuť po kliknutí na tlačidlo vo formáte pdf.";
            document.getElementById("id34").value = "Stiahnuť PDF";
            });
        });

        document.getElementById("eng-button").addEventListener("click", function() {
        fetch('../../bilingual.json')
            .then(response => response.json())
            .then(data => {
            document.getElementById("id31").textContent = data.eng.id31;
            document.getElementById("pdfContent").innerHTML = "After logging in, the student can see the available tasks that he can complete, and on the next subpage there is this manual, which can also be downloaded by clicking the button in pdf format.";
            document.getElementById("id34").value = "Download PDF";
            });
        });
    </script>
</body>
</html>
