<?php
session_start();
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variable id from POST request
$car_id = '';
if (isset($_POST['id'])) {
    $car_id = $_POST['id'];
}

// Delete method
$success = $database->deleteCar($_SESSION["id"], $car_id);


// Check result
if ($success == 1) {
    echo "<p class='phpecho'>Car with ID: '{$car_id}' successfully deleted!</p>";
} else {
    echo "<p class='phpecho'> Car with ID: '{$car_id}' does not exist!</p>";
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
