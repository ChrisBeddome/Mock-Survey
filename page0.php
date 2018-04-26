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
//This page presents the user with a brief introductory message and a button to begin the survey
//form handling methods are not necessary here since there is no data to be processed.
//submitForm() and generateForm() are still used to keep the control flow consistent with the rest of the application


$page = 0;
session_start();

$_SESSION["page"] = $page;

//if page loaded via a form submission from same page, submit form.
if (isset($_GET["mode"]) && $_GET["mode"] == "submit") {
  submitForm();
} else { //else generate form
  generateForm();
}

//If this is the furthest the user has been, set "progress" session variable to this page
//Then load the next page
function submitForm() {
  if ($_SESSION["progress"] < $_SESSION["page"]) {
    $_SESSION["progress"] = $_SESSION["page"];
  }

  header("location: page1.php");
}

//generate simple form which explains the survey to users
function generateForm() {
  ?>
  <p>This survey will ask you a few questions about yourself and your purchases with us. Please answer all questions on each page before proceeding to the next page.</p> 
  <form action="page0.php?mode=submit" method="post">
    <input class="button buttonright" type="submit" value="Begin">
  </form>
  
  <?php
}//end generateForm
?>

<div id="clearfix"></div>
</div>
</body>
</html>



