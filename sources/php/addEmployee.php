<?php
session_start();

require_once('DatabaseHelper.php');

$database = new DatabaseHelper();

//Grab variables from POST request
$name = '';
if (isset($_POST['name'])) {
    $name = $_POST['name'];
}

$surname = '';
if (isset($_POST['surname'])) {
    $surname = $_POST['surname'];
}

// Insert method
$success = $database->insertIntoEmployee($name, $surname, $_SESSION["id"]);

// Check result
if ($success) {
    echo "<p class='phpecho'> {$name} {$surname} successfully added!</p>";
} else {
    echo "<p class='phpecho'>Error can't insert  {$name} {$surname}!</p>";
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
