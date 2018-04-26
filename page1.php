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
//This page presents the user with a form asking them for basic personal information
//Upon submission, the same page is served, but with the "mode" GET variable set to "submit"
//This page can also be served with the "edit" GET variable set to true, this occurs when the user is editing an answer from the review table at the end of the survey

$page = 1;
session_start();

//if user is trying to access this page before completing previous page, redirect to index
if (!isset($_SESSION["progress"]) || $_SESSION["progress"] < $page - 1) {
  header("location: index.php");  
} else {
  $_SESSION["page"] = $page;
}

//if form has been submitted, validate form. else generate the form
if (isset($_GET["mode"]) && $_GET["mode"] == "submit") {
  validateForm();
} else {
  //call generateForm with empty error array
  generateForm([]);
}

//check form for errors, if errors are found, render the form again with notices for each error
function validateForm() {
  $name = $_POST["fullName"];
  $age = $_POST["age"];
  $student = $_POST["student"];

  $errors = [];

  //name field must not be empty, must be 100 characters or less (to coincide with database constraints)
  if (strlen(trim($name)) < 1) {
    $errors["name"] = "Name field must not be empty";
  } else if (strlen($name) > 100) { 
    $errors["name"] = "Name field must be 100 characters or less";
  }

  //age must not be empty, it must be numeric
  if (strlen(trim($age)) < 1) {
    $errors["age"] = "Age field must not be empty";
  } else if (!is_numeric($age)) {
    $errors["age"] = "Age field must be numeric value";
  }

  //empty option cannot be submitted
  if (strlen($student) < 1) {
    $errors["student"] = "Must select an option";
  }

  //if there are errors:
    // Set the progress session variable to the page before this page
      //this is necessary in the event that this page has already been completed, but is being resubmitted after the fact
    //Unset the edit GET variable. Since errors are present, we must ensure there are no reprecussions in the following pages, so we cannot simply jump back to the edit table
    //generate the form with the $errors array as an argument

  //if there are no errors, submit the form
  if (count($errors) > 0) {
    $_SESSION["progress"] = $_SESSION["page"] - 1;
    unset($_GET["edit"]);
    generateForm($errors);
  } else {
    submitForm();
  }
}

//updates the session data variables and the session page progress variable, redirects to next page
function submitForm() {
  $name = $_POST["fullName"];
  $age = $_POST["age"];
  $student = $_POST["student"];

  $_SESSION["userData"]["name"] = $name;
  $_SESSION["userData"]["age"] = $age;
  $_SESSION["userData"]["student"] = $student;

  //if this is the furthest the user has gotten in the survey, set the progress to this page
  if ($_SESSION["progress"] < $_SESSION["page"]) {
    $_SESSION["progress"] = $_SESSION["page"];
  }

  //if in edit mode, jump back to the edit table, else go to next page
  if (isset($_GET["edit"]) && $_GET["edit"] == "true") {
    header("location: page4.php");
  } else {
    header("location: page2.php");
  }
  
}


function generateForm($errors) {

  //The following variables attempt to pull data from the POST variable (in the event of an unvalidated submission)
  //If no data is found in POST, we attempt to pull data from the session variable (in the event this page has already been completed)
  //if no data is present, the fields remain empty
  $fullname = "";
  if (isset($_POST["fullName"])) {
    $fullname = $_POST["fullName"];  
  } else if (isset($_SESSION["userData"]["name"])) {
    $fullname = $_SESSION["userData"]["name"];
  }

  $age = "";
  if (isset($_POST["age"])) {
    $age = $_POST["age"];  
  } else if (isset($_SESSION["userData"]["age"])) {
    $age = $_SESSION["userData"]["age"];
  }

  $student = "";
  if (isset($_POST["student"])) {
    $student = $_POST["student"];  
  } else if (isset($_SESSION["userData"]["student"])) {
    $student = $_SESSION["userData"]["student"];
  }

  ?>
  <form id="form1" action="page1.php?mode=submit<?php echo (isset($_GET["edit"]) && $_GET["edit"] == "true") ? "&edit=true" : ""?>" method="post">

    
    <?php 
      //display errors if any exist for name field
      if (isset($errors["name"])) {
      ?><div class="error"><?php echo $errors["name"]?></div>
      <?php
    }?>

    <label>Full Name: 
      <input type="text" name="fullName" value="<?php echo $fullname ?>">
    </label>


    <?php 
      //display errors if any exist for age field
      if (isset($errors["age"])) {
      ?><div class="error"><?php echo $errors["age"]?></div>
      <?php
    }?> 
  

    <label>Your Age: 
      <input type="text" name="age" value="<?php echo $age?>">
    </label>

    <?php 
      //display errors if any exist for student field
      if (isset($errors["student"])) {
      ?><div class="error"><?php echo $errors["student"]?></div>
      <?php
    }?> 

    <label>Are You a Student? 
      <select name="student">
        <option value=""></option>
        <option value="full time" <?php echo $student == "full time" ? "selected" : ""?> >Yes, Full Time</option>
        <option value="part time" <?php echo $student == "part time" ? "selected" : ""?>>Yes, Part Time</option>
        <option value="no" <?php echo $student == "no" ? "selected" : ""?>>No</option>
      </select>
    </label>

    <!--if we are in edit mode, the text for the button should read "Submit Changes" instead of "next"-->
    <input class="button buttonright" type="submit" value="<?php echo (isset($_GET["edit"]) && $_GET["edit"] == "true") ? "Submit Changes" : "Next" ?>">

  </form>
  
  <?php
}// end generateForm
 
?>
<button class="button buttonleft" onclick="location.href='page0.php'">Back</button>

<div id="clearfix"></div>
</div>
</body>
</html>

