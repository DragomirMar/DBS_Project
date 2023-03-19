<title>Best Cars</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">

<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variables from POST request
$country = '';
if (isset($_POST['country'])) {
    $country = $_POST['country'];
}

$city = '';
if (isset($_POST['city'])) {
    $city = $_POST['city'];
}

$address = '';
if (isset($_POST['address'])) {
    $address = $_POST['address'];
}

// Insert method
$success = $database->insertIntoLocation($city, $country, $address);

// Check result
if ($success) {
    echo "<p class='phpecho'> Location successfully added!</p>";
} else {
    echo "<p class='phpecho'>Error can't insert Location '{$country} {$city} {$address}'!</p>";
}
?>


<!-- link back to index page-->
<br>
<div style="
    width: 8%;
    margin: auto;
    align-items: center">
    <a href="index.php">
        <button class="button2"> go back</button>
    </a>
</div>

