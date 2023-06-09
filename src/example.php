<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$student='student';
require_once('private/config.php');

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || strcmp($student,$_SESSION["role"]) != 0){
    header("Location: index.php");
    exit;
}

if(!isset($_SESSION['exampleID'])){
    header("Location: logedStudent.php");
    exit;
}

$exampleID = $_SESSION['exampleID'];
unset($_SESSION['exampleID']);

// Insert example to the database only if it is not there already
$stmt = $pdo->prepare("SELECT COUNT(*) FROM generatedExamples WHERE id_student = :id_student AND id_example = :id_example");
$stmt->bindParam(':id_student', $_SESSION['id']);
$stmt->bindParam(':id_example', $exampleID);
$stmt->execute();
$rowCount = $stmt->fetchColumn();

if ($rowCount == 0) {
    $sql = "INSERT INTO generatedExamples (id_student, id_example) VALUES (:id_student, :id_example)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_student', $_SESSION['id']);
    $stmt->bindParam(':id_example', $exampleID);
    $stmt->execute();
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Test</title>
    <script>
        MathJax = {
            tex: {
                inlineMath: [['$', '$'], ['\\(', '\\)']]
            },
            svg: {
                fontCache: 'global'
            }
        };
    </script>
    <script type="text/javascript" id="MathJax-script" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.2/es5/tex-svg.min.js"></script>
    <link rel="stylesheet" href="public/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.css" />
</head>
<body>
<div class="buttonsForBil">
    <button id="sk-button">SK</button>
    <button id="eng-button">ENG</button>
</div>
<nav>
    <li><a href="logedStudent.php" class="active"><img src = "photos/profile-circle-svgrepo-com.svg" alt="student"/></a></li>
    <li><a href="logout.php"><img src = "photos/log-out-svgrepo-com.svg" alt="logout"/></a></li>
</nav>
<?php  echo "<h3 id='id48'>User: " .$_SESSION["meno"]. " " .$_SESSION["priezvisko"]. " (student)</h3>";?>
<section class="myDivForEx">
<div>
    <?php

    $sql = "SELECT * FROM examples WHERE id = :id;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $exampleID);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<h2>" . $result['example_name'] . "</h2>";

    // Replace \includegraphics{...} part with the image
    $pattern = '/\\\\includegraphics{([^}]+)}/';
    $matches = [];
    $text = $result['example_body'];

    if (preg_match($pattern, $text, $matches)) {
        $imageFilename = $matches[1];

        $imageTag = '<img src="' . $imageFilename . '" alt="Picture" class="responsive-image">';

        $modifiedText = preg_replace($pattern, $imageTag, $text);

        echo $modifiedText;
    } else {
        echo $text;
    }

    ?>
</div>
<script type="text/x-mathjax-config">
        MathJax.Hub.Config({
        tex2jax: {
        inlineMath: [['$', '$']]
        }
        });
</script>


<div class="myDivForSt">
    <p id="id55">Solution:</p>
    <p><span id="math-field"></span></p>
    <form id="form" action="" method="post">
    <input id="latex" name="latex" type="hidden" value="">
    <input id="exampleID" name="exampleID" type="hidden" value="<?php echo $exampleID; ?>">
    <button type="button" id='id30' onclick="submitForm()">Submit</button>
    </form>
</div>
</section>
<script>
    function submitForm() {
        let formData = $('#form').serialize();

        $.ajax({
            type: 'POST',
            url: 'checkSolution.php',
            data: formData,
            success: function(response) {
                alert(response.message);
                window.location.href = 'logedStudent.php';
            },
            error: function(xhr, status, error) {
                console.log(xhr + " " + status + " " + error);
            }
        });
    }

    $(document).ready(function() {
        var mathFieldSpan = document.getElementById('math-field');
        var latex = document.getElementById('latex');

        var MQ = MathQuill.getInterface(2);
        var mathField = MQ.MathField(mathFieldSpan, {
            spaceBehavesLikeTab: true,
            handlers: {
                edit: function() {
                    latex.value = mathField.latex();
                }
            }
        });
    });
</script>
<script>
    document.getElementById("sk-button").addEventListener("click", function() {
    fetch('bilingual.json')
        .then(response => response.json())
        .then(data => {
        document.getElementById("id55").textContent = data.sk.id55;
        var first = "<?php echo $_SESSION["meno"]; ?>";
        var second = "<?php echo $_SESSION["priezvisko"]; ?>";
        var elem = "<h3 id='id48'>Používatel: " + first + " " + second + " (študent)</h3>";
        document.getElementById("id48").innerHTML = elem;
        document.getElementById("id30").textContent = data.sk.id30;

        });
    });

    document.getElementById("eng-button").addEventListener("click", function() {
    fetch('bilingual.json')
        .then(response => response.json())
        .then(data => {
        document.getElementById("id55").textContent = data.eng.id55;
        var first = "<?php echo $_SESSION["meno"]; ?>";
        var second = "<?php echo $_SESSION["priezvisko"]; ?>";
        var elem = "<h3 id='id48'>User: " + first + " " + second + " (student)</h3>";
        document.getElementById("id48").innerHTML = elem;
        document.getElementById("id30").textContent = data.eng.id30;

        });
    });
</script>

</body>
</html>
