<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$student='student';

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || strcmp($student,$_SESSION["role"]) != 0){
    header("Location: index.php");
    exit;
}

require_once('private/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['latex']) && isset($_POST['exampleID'])) {

    $sql = "SELECT solution FROM examples WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $_POST['exampleID'], PDO::PARAM_INT);
    $stmt->execute();
    $solution = $stmt->fetch(PDO::FETCH_ASSOC)['solution'];

    if(trim(normalizeTeX($_POST['latex'])) === trim(normalizeTeX($solution))) {
        $status = 1;
    } else {
        $status = 0;
    }

    echo $_POST['latex'] . " " . $solution;

    $sql = "UPDATE generatedExamples SET status = :status WHERE id_student = :id_student AND id_example = :id_example";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_student', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->bindParam(':id_example', $_POST['exampleID'], PDO::PARAM_INT);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->execute();

    unset($_POST['latex']);
    unset($_POST['exampleID']);
}

function normalizeTeX($tex) {

    // Replace \dfrac with \frac
    $tex = str_replace('\dfrac', '\frac', $tex);

    // Normalize whitespace
    $tex = trim(preg_replace('/\s+/', ' ', $tex));

    // Removing extra space around brackets
    $tex = preg_replace('/\s*{\s*/', '{', $tex);
    $tex = preg_replace('/\s*}\s*/', '}', $tex);

    return $tex;
}
