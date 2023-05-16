<?php
if (isset($_POST['csv_data'])) {
  $csvData = $_POST['csv_data'];
  $filename = 'export.csv';
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="' . $filename . '"');
  $output = fopen('php://output', 'w');
  fwrite($output, $csvData);
  fclose($output);
  exit();
}
?>
