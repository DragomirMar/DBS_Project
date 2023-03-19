<?php
session_start();

require_once('DatabaseHelper.php');

$database = new DatabaseHelper();

//Grab variables from POST request
$brand = '';
if (isset($_POST['brand'])) {
    $brand = $_POST['brand'];
}

$model = '';
if (isset($_POST['model'])) {
    $model = $_POST['model'];
}

$leasingNr = '';
if (isset($_POST['leasingNr'])) {
    $leasingNr = $_POST['leasingNr'];
}

$success = $database->insertIntoCar($model, $brand, $leasingNr, $_SESSION["id"]);

// Check result
if ($success) {
    echo "<p class='phpecho'> {$brand} {$model} successfully added!</p>";
} else {
    echo "<p class='phpecho'>Error can't insert Car {$brand} {$model} {$leasingNr}!</p>";
}
?>
<title>Best Cars</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">

<!-- link back to previous page-->
<br>
<div style="
    width: 8%;
    margin: auto;
    align-items: center">
    <a href="chosenLocation.php">
        <button class="button2"> go back</button>
    </a>
</div>
