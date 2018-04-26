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
//This page asks the user to review the information they submitted and ensure everything is correct
//an "edit" button besie each value directs to the page in which that value is entered
//the GET variable "edit" is set to true upon editing a value, which allows the user to come back directly to this page after changing the value

$page = 4;
session_start();

//if user is trying to access this page before completing previous page, redirect to index
if (!isset($_SESSION["progress"]) || $_SESSION["progress"] < $page - 1) {
  header("location: index.php");  
} else {
  $_SESSION["page"] = $page;
}

$name = $_SESSION["userData"]["name"];
$age = $_SESSION["userData"]["age"];
$student = $_SESSION["userData"]["student"];
$purchaseMethod = $_SESSION["userData"]["howPurchased"];
$purchases = $_SESSION["userData"]["purchases"]; 
?>

<p><strong>Please ensure the following information is correct before submitting your survey:</strong></p>
<br>

<table>
  <tr>
    <td>Name:</td> 
    <td><?php echo $name ?></td>
    <td></td>
    <td><button class="button buttonright" onclick="location.href='page1.php?edit=true'">Edit</button></td>
    
  </tr>
  <tr>
    <td>Age:</td>
    <td><?php echo $age ?></td>
    <td></td>
    <td><button class="button buttonright" onclick="location.href='page1.php?edit=true'">Edit</button></td>
  </tr>
  <tr>
    <td>Student Status:</td>
    <td><?php echo $student ?></td>
    <td></td>
    <td><button class="button buttonright" onclick="location.href='page1.php?edit=true'">Edit</button></td>
  </tr>
  <tr>
    <td>Purchase Method:</td>
    <td><?php echo $purchaseMethod ?></td>
    <td></td>
    <td><button class="button buttonright" onclick="location.href='page2.php?edit=true'">Edit</button></td>
  </tr>
</table>

<br>

<table>
  <thead>
    <tr>
      <th>Product</th>
      <th>Rating</th>
      <th>Would Recommend?</th>
    </tr>
  </thead>
  <?php 
    foreach ($purchases as $item=>$values) {
      ?>
        <tr>
          <td><?php echo $item ?></td>
          <td><?php echo $values["satisfaction"] ?></td>
          <td><?php echo $values["recommend"]?></td>
          <td><button class="button buttonright" onclick="location.href='page3.php?edit=true'">Edit</button></td>
        </tr>
      <?php
    }//end foreach
  ?>
</table>

<br>

<button class="button buttonright" onclick="location.href='submit.php'">Submit</button>

<div id="clearfix"></div>
</div>
</body>
</html>