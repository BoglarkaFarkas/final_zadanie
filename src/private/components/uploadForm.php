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
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $targetDir = "../../examples/";
    $uploadOk = 1;
    $allowedExtensions = array("jpg","jpeg", "tex");
    $extension = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
    if (in_array($extension, $allowedExtensions)) {
        if ($extension === "jpg" || $extension === "jpeg") {
            $targetDir = "../../examples/images/";
        }
        $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);

        // Skontroluje, či súbor už existuje
        if (file_exists($targetFile)) {
            $displayStyle = 'block'; 
            //echo '<div class="alert alert-danger">Súbor s týmto názvom už existuje.</div>';
            $uploadOk = 0;
        }

        // Skontroluje, či je súbor správneho typu
        if  ($extension === "jpg" || $extension === "jpeg") {
            // skontrolovať, či je súbor SVG
            if ($_FILES["fileToUpload"]["type"] !== "image/jpeg" && $_FILES["fileToUpload"]["type"] !== "image/jpg")  {
                $displayStyle1 = 'block'; 
                //echo '<div class="alert alert-danger">Obrázky môžu byť iba vo formáte jpg.</div>';
                $uploadOk = 0;
            }
        } elseif ($extension === "tex") {
            // skontrolovať, či je súbor TeX
            if ($_FILES["fileToUpload"]["type"] !== "application/x-tex" && $_FILES["fileToUpload"]["type"] !== "application/x-latex") {
                $displayStyle2 = 'block'; 
                //echo '<div class="alert alert-danger">Zadania môžu byť iba vo formáte tex.</div>';
                $uploadOk = 0;
            }
        }

        // Skontroluje veľkosť súboru
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $displayStyle3 = 'block'; 
            //echo '<div class="alert alert-danger">Ospravedlňujeme sa, súbor je príliš veľký.</div>';
            $uploadOk = 0;
        }

        // Skontroluje, či $uploadOk je nastavené na 0 z nejakej chyby
        if ($uploadOk == 0) {
            $displayStyle4 = 'block'; 
            //echo '<div class="alert alert-danger">Ospravedlňujeme sa, súbor sa nepodarilo nahrať.</div>';
        } else {
            // Pokús sa nahrať súbor
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                echo '<div class="alert alert-success">Súbor '. basename($_FILES["fileToUpload"]["name"]) . ' bol úspešne nahraný.</div>';
            } else {
                $displayStyle6 = 'block'; 
                //echo '<div class="alert alert-danger">Ospravedlňujeme sa, vyskytla sa chyba pri nahrávaní súboru.</div>';
            }
        }
    } else {
        $displayStyle7 = 'block'; 
        //echo '<div class="alert alert-danger">Ospravedlňujeme sa, nepovolená prípona súboru.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <!------------------------------------------CSS---------------------------------------->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link href="../../public/css/upload-form.css" rel="stylesheet">
    <link href="../../public/css/nav.css" rel="stylesheet">
    <style>
        #id40,#id41,#id42,#id43,#id44,#id45,#id46{
            display:none;
        }
    </style>
    
</head>
<body>
<div class="alert alert-danger" id="id40" style="display: <?php echo $displayStyle; ?>">Súbor s týmto názvom už existuje.</div>
<div class="alert alert-danger" id="id41" style="display: <?php echo $displayStyle1; ?>">Obrázky môžu byť iba vo formáte jpg.</div>
<div class="alert alert-danger" id="id42" style="display: <?php echo $displayStyle2; ?>">Zadania môžu byť iba vo formáte tex.</div>
<div class="alert alert-danger" id="id43" style="display: <?php echo $displayStyle3; ?>">Ospravedlňujeme sa, súbor je príliš veľký.</div>
<div class="alert alert-danger" id="id44" style="display: <?php echo $displayStyle4; ?>">Ospravedlňujeme sa, súbor sa nepodarilo nahrať.</div>
<div class="alert alert-danger" id="id45" style="display: <?php echo $displayStyle6; ?>">Ospravedlňujeme sa, vyskytla sa chyba pri nahrávaní súboru.</div>
<div class="alert alert-danger" id="id46" style="display: <?php echo $displayStyle7; ?>">Ospravedlňujeme sa, nepovolená prípona súboru.</div>

<div class="buttonsForBil">
    <button id="sk-button">SK</button>
    <button id="eng-button">ENG</button>
  </div>
    <nav>
        <li><a href="../../loged.php"><img src = "../../photos/add-circle-svgrepo-com.svg" alt="student"/></a></li>
        <li><a href="stats.php"><img src = "../../photos/statistics.svg" alt="stats"/></a></li>
        <li><a href="teacherPdf.php"> <img src = "../../photos/guide-link-svgrepo-com.svg" alt="man"/></a></li>
        <li><a href="uploadForm.php" class="active"><img src = "../../photos/upload-svgrepo-com.svg" alt="upload"/></a></li>
        <li><a href="../../logout.php"><img src = "../../photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
    </nav>
    <section>
        <div class="container">
            <form method="post" enctype="multipart/form-data">
                <h1 id="id32">Nahraj súbor</h1>
                <div class="my-3">
                    <input class="form-control" type="file" id="fileToUpload" name="fileToUpload">
                </div>
                <div class="input-box button">
                    <input type="submit" id="id33" value="Upload" name="submit">
                </div>
            </form>
        </div>
    </section>

    <!------------------------------------------JS---------------------------------------->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <script>

        document.getElementById("sk-button").addEventListener("click", function() {
        fetch('../../bilingual.json')
            .then(response => response.json())
            .then(data => {
            document.getElementById("id32").textContent = data.sk.id32;
            document.getElementById("id33").value = "Nahrať";
            document.getElementById("id40").textContent = data.sk.id40;
            document.getElementById("id41").textContent = data.sk.id41;
            document.getElementById("id42").textContent = data.sk.id42;
            document.getElementById("id43").textContent = data.sk.id43;
            document.getElementById("id44").textContent = data.sk.id44;
            document.getElementById("id45").textContent = data.sk.id45;
            document.getElementById("id46").textContent = data.sk.id46;
            });
        });

        document.getElementById("eng-button").addEventListener("click", function() {
        fetch('../../bilingual.json')
            .then(response => response.json())
            .then(data => {
            document.getElementById("id32").textContent = data.eng.id32;
            document.getElementById("id33").value = "Upload";
            document.getElementById("id40").textContent = data.eng.id40;
            document.getElementById("id41").textContent = data.eng.id41;
            document.getElementById("id42").textContent = data.eng.id42;
            document.getElementById("id43").textContent = data.eng.id43;
            document.getElementById("id44").textContent = data.eng.id44;
            document.getElementById("id45").textContent = data.eng.id45;
            document.getElementById("id46").textContent = data.eng.id46;
            });
        });
    </script>
</body>
</html>
