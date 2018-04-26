<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Survey Application</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header></header>

<div id="container">

<?php
//This page submits all information to the database and prints a thank you message to the screen

session_start();

//if user is trying to access this page before completing previous page, redirect to index
if (!isset($_SESSION["progress"]) || $_SESSION["progress"] < 3) {
  header("location: index.php");  
}

submitToDatabase();

function submitToDatabase() {
  $name = $_SESSION["userData"]["name"];
  $age = $_SESSION["userData"]["age"];
  $student = $_SESSION["userData"]["student"];
  $purchaseMethod = $_SESSION["userData"]["howPurchased"];
  $purchases = $_SESSION["userData"]["purchases"];

  $conn = new mysqli('localhost', 'project1User', 'proj1', 'project1');
	if ($conn->connect_errno) {
		die ("Could not connect to database server\n Error: ".$conn->connect_errno ."\n Report: ".$conn->connect_error."\n");
  }

  //submit user information to database
  $query = "INSERT INTO users (fullname, age, student) VALUES ('$name', $age, (SELECT id FROM studentstatus WHERE status = '$student'))";

  if ($conn->query($query)) {
    $id = $conn->insert_id;
  } else {
    die("Error: " . $query . "<br>" . $conn->error);
  }

  //submit review information to database for each item that the user reviewed
  foreach ($purchases as $item=>$values) {

    $rating = $values["satisfaction"];
    $recommend = $values["recommend"];

    $query = "INSERT INTO reviews (user, product, purchaseMethod, rating, recommend) VALUES ($id, (SELECT id FROM products WHERE name = '$item'), (SELECT id FROM purchasemethods WHERE method = '$purchaseMethod'), $rating, '$recommend')";

    if (!$conn->query($query)) {
      die("Error: " . $query . "<br>" . $conn->error);
    }   
  }

  echo "<p>Data successfully submitted to database. Thank you for your participation</p>";
}

//destroy session
session_unset();
session_destroy();
?>

</div>
</body>
</html>