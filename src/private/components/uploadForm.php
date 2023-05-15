<?php 
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
            echo '<div class="alert alert-danger">Súbor s týmto názvom už existuje.</div>';
            $uploadOk = 0;
        }
    
        // Skontroluje, či je súbor správneho typu
        if  ($extension === "jpg" || $extension === "jpeg") {
            // skontrolovať, či je súbor SVG
            if ($_FILES["fileToUpload"]["type"] !== "image/jpeg" && $_FILES["fileToUpload"]["type"] !== "image/jpg")  {
                echo '<div class="alert alert-danger">Obrázky môžu byť iba vo formáte jpg.</div>';
                $uploadOk = 0;
            }
        } elseif ($extension === "tex") {
            // skontrolovať, či je súbor TeX
            if ($_FILES["fileToUpload"]["type"] !== "application/x-tex" && $_FILES["fileToUpload"]["type"] !== "application/x-latex") {
                echo '<div class="alert alert-danger">Zadania môžu byť iba vo formáte tex.</div>';
                $uploadOk = 0;
            }
        }
    
        // Skontroluje veľkosť súboru
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo '<div class="alert alert-danger">Ospravedlňujeme sa, súbor je príliš veľký.</div>';
            $uploadOk = 0;
        }
    
        // Skontroluje, či $uploadOk je nastavené na 0 z nejakej chyby
        if ($uploadOk == 0) {
            echo '<div class="alert alert-danger">Ospravedlňujeme sa, súbor sa nepodarilo nahrať.</div>';
        } else {
            // Pokús sa nahrať súbor
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                echo '<div class="alert alert-success">Súbor '. basename($_FILES["fileToUpload"]["name"]) . ' bol úspešne nahraný.</div>';
            } else {
                echo '<div class="alert alert-danger">Ospravedlňujeme sa, vyskytla sa chyba pri nahrávaní súboru.</div>';
            }
        }
    } else {
        echo '<div class="alert alert-danger">Ospravedlňujeme sa, nepovolená prípona súboru.</div>';
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
</head>
<body>
    <section>
        <div class="container">
            <form method="post" enctype="multipart/form-data">
                <div class="title">Nahraj súbor</div>
                <div class="my-3">
                    <input class="form-control" type="file" id="fileToUpload" name="fileToUpload">
                </div>
                <div class="input-box button">
                    <input type="submit" value="Upload" name="submit">
                </div>    
            </form>
        </div>
    </section>

    <!------------------------------------------JS---------------------------------------->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>
</html>