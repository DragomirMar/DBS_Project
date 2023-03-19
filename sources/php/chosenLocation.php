<?php
session_start();

require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

$id = '';
if (isset($_POST['id'])) {
    $id = $_POST['id'];
}

if ($id != null) {
    $_SESSION["id"] = $id;
}

$CarID = '';
if (isset($_GET['aid'])) {
    $CarID = $_GET['aid'];
}

$brand = '';
if (isset($_GET['marke'])) {
    $brand = $_GET['marke'];
}

$model = '';
if (isset($_GET['modell'])) {
    $model = $_GET['modell'];
}

$leasingNr = '';
if (isset($_GET['lnr'])) {
    $leasingNr = $_GET['lnr'];
}

$name = '';
if (isset($_GET['vorname'])) {
    $name = $_GET['vorname'];
}
$surname = '';
if (isset($_GET['nachname'])) {
    $surname = $_GET['nachname'];
}
$mid = '';
if (isset($_GET['mid'])) {
    $mid = $_GET['mid'];
}

$locationName = $database->getLocationName($_SESSION["id"]);

$cars_array = $database->selectCarsFromLocation($CarID, $brand, $model, $leasingNr, $_SESSION["id"]);
?>
<html>
<head>
    <meta charset="utf-8">
    <title>Best Cars</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="image1">
    <?php foreach ($locationName as $name): ?>
        <h1 style="color: cadetblue; text-shadow: 5px 3px 7px black;
-webkit-text-stroke-width: 1px;
  -webkit-text-stroke-color: steelblue;"><?php echo $name['STADT'] ?></h1>
    <?php endforeach; ?>
</div>


<br>

<h2>Available Cars:</h2>

<div class="tableContainer">
    <table class="table">
        <thead class="head">
        <tr>
            <th class="col">ID</th>
            <th class="col">Brand</th>
            <th class="col">Model</th>
            <th class="col4">Leasing Number</th>
        </tr>
        </thead>
        <?php foreach ($cars_array as $car): ?>
            <tr>
                <td><?php echo $car['AID']; ?>  </td>
                <td><?php echo $car['MARKE']; ?>  </td>
                <td><?php echo $car['MODELL']; ?>  </td>
                <td><?php echo $car['LNR']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<br>

<!-- Add Car -->
<div class="form">
    <h3>Add Car: </h3>

    <form method="post" action="addCar.php">
        <!-- ID is not needed, because its autogenerated by the database -->
        <table class="table2">
            <!-- Brand textbox -->
            <tr class="spaceunder">
                <td><label for="brand">Brand:</label></td>
                <td><input id="brand" name="brand" type="text" maxlength="20"></td>
            </tr>
            <br>

            <!-- Model textbox -->
            <tr class="spaceunder">
                <td><label for="model">Model:</label></td>
                <td><input id="model" name="model" type="text" maxlength="20"></td>
            </tr>
            <br>

            <!-- Leasing textbox -->
            <tr>
                <td><label for="leasingNr">Leasing:</label></td>
                <td><input id="leasingNr" name="leasingNr" type="text" maxlength="20"></td>
            </tr>

        </table>
        <br>


        <!-- Submit button -->
        <div style="margin: auto;
                width: 18%;
                padding: 20px;">
            <input type="submit" name="button" value="Add Car">

        </div>

    </form>
</div>

<div class="form">
    <!-- Delete Car -->
    <h3>Delete Car: </h3>
    <form method="post" action="delCar.php">
        <!-- ID textbox -->
        <table class="table2">
            <tr class="spaceover">
                <td><label for="del_name">ID:</label></td>
                <td><input id="del_name" name="id" type="number" min="0"></td>
            </tr>
        </table>
        <br>
        <!-- Submit button -->
        <div class="button">
            <button type="submit">
                Delete Car
            </button>
        </div>
    </form>
</div>

<br>
<br>
<br>

<!-- Add Employee -->
<div class="form">
    <h3>Add Employee: </h3>

    <form method="post" action="addEmployee.php">
        <!-- ID is not needed, because its autogenerated by the database -->
        <table class="table2">
            <!-- Name textbox -->
            <tr class="spaceunder">
                <td><label for="name">Name:</label></td>
                <td><input id="name" name="name" type="text" maxlength="20"></td>
            </tr>
            <br>

            <!-- Surname textbox -->
            <tr class="spaceunder">
                <td><label for="surname">Surname:</label></td>
                <td><input id="surname" name="surname" type="text" maxlength="20"></td>
            </tr>
            <br>

        </table>
        <br>


        <!-- Submit button -->
        <div style="margin: auto;
                width: 18%;
                padding: 20px;">
            <input type="submit" name="button" value="Add Employee">

        </div>

    </form>
</div>

<div class="form">
    <!-- Delete Employee -->
    <h3>Delete Employee: </h3>
    <form method="post" action="delEmployee.php">
        <!-- ID textbox -->
        <table class="table2">
            <tr class="spaceover">
                <td><label for="id">ID:</label></td>
                <td><input id="id" name="id" type="number" min="0"></td>
            </tr>
        </table>
        <br>
        <!-- Submit button -->
        <div class="button">
            <button type="submit">
                Delete Employee
            </button>
        </div>
    </form>
</div>
<br>
<br>

<?php $employee_array = $database->selectEmployeesFromLocation($_SESSION["id"]); ?>
<h3 style="font-size: 45px; margin-right:850px"">Our Staff Members:</h2>

<div style="padding: 0px;
     height: 250px;
     width: 450px;
     overflow: hidden;
     overflow-y: auto;
     border-radius: 15px;
     margin-left: auto;
     margin-right: auto;
     border: 1px solid;">
    <table class="table">
        <thead class="head1">
        <tr>
            <th class="col">ID</th>
            <th class="col">Name</th>
            <th class="col">Surname</th>
        </tr>
        </thead>
        <?php foreach ($employee_array as $emp): ?>
            <tr>
                <td><?php echo $emp['MID']; ?>  </td>
                <td><?php echo $emp['VORNAME']; ?>  </td>
                <td><?php echo $emp['NACHNAME']; ?>  </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>


<?php $sales_array = $database->selectSales($_SESSION["id"]); ?>
<h3 style="font-size: 45px; margin-right:850px">Sales:</h3>

<div style="padding: 0px;
    height: 300px;
    width: 850px;
    overflow: hidden;
    overflow-y: auto;
    margin-left: auto;
    margin-right: auto;">
    <table class="table">
        <thead class="head">
        <tr>
            <th class="col">MID</th>
            <th class="col">Name</th>
            <th class="col">Surname</th>
            <th class="col">Brand</th>
            <th class="col">Model</th>
            <th class="col">Date</th>
            <th class="col">Price</th>
        </tr>
        </thead>
        <?php foreach ($sales_array as $sale): ?>
            <tr>
                <td><?php echo $sale['MID']; ?>  </td>
                <td><?php echo $sale['VORNAME']; ?>  </td>
                <td><?php echo $sale['NACHNAME']; ?>  </td>
                <td><?php echo $sale['MARKE']; ?>  </td>
                <td><?php echo $sale['MODELL']; ?>  </td>
                <td><?php echo $sale['DATUM']; ?>  </td>
                <td><?php echo $sale['SUM'] . " €" ?>  </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>


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

</body>
</html>