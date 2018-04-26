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
//This page asks the user to enter a "satisfaction" rating and "would recommend" boolean for each item purchased
//Upon submission, the same page is served, but with the "mode" GET variable set to "submit"
//This page can also be served with the "edit" GET variable set to true, this occurs when the user is editing an answer from the review table at the end of the survey

$page = 3;
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
  $errors = [];

  //must set to empty array if nothing exists so the following loops do not throw errors
  if (isset($_POST["satisfaction"])) {
    $satisfaction = $_POST["satisfaction"];
  } else {
    $satisfaction = [];
  }
  
  $recommend = $_POST["recommend"];

  //loop through each product, if user has not set a "would recommend" value, generate an error
  foreach ($recommend as $item=>$rating) {
    if (strlen($rating) < 1) {
      $errors[$item]["recommend"] = "Must select an option for this item";
    }
  }

   //loop through each product, if user has not set a satisfaction value, generate an error
  foreach ($recommend as $item=>$value) {
    if (!isset($satisfaction[$item])) {
      $errors[$item]["satisfaction"] = "Must enter a satisfaction rating for this item";
    }
  } 

  //if there are errors:
    // Set the progress session variable to the page before this page
      //this is necessary in the event that this page has already been completed, but is being resubmitted after the fact
    //Unset the edit GET variable. Since errors are present, we must ensure there are no reprecussions in the following pages, so we cannot simply jump back to the edit table
    //generate the form with the $errors array as an argument

  //if there are no errors, submit the form
  if (count($errors) > 0) {
    generateForm($errors);
    $_SESSION["progress"] = $_SESSION["page"] - 1;
  } else {
    submitForm();
  }
}

//updates the session data variables and the session page progress variable, redirects to next page
function submitForm() {
  $satisfaction = $_POST["satisfaction"];
  $recommend = $_POST["recommend"];

  foreach ($satisfaction as $item=>$rating) {
    $_SESSION["userData"]["purchases"][$item]["satisfaction"] = $rating;
  }

  foreach($recommend as $item=>$value) {
    $_SESSION["userData"]["purchases"][$item]["recommend"] = $value;
  }

   //if this is the furthest the user has gotten in the survey, set the progress to this page
  if ($_SESSION["progress"] < $_SESSION["page"]) {
    $_SESSION["progress"] = $_SESSION["page"];
  }

  header("location: page4.php");
}

function generateForm($errors) {
  ?>
  <form id="form3" action="page3.php?mode=submit" method="post">
  <?php

   //The following variables attempt to pull data from the POST variable (in the event of an unvalidated submission)
  //If no data is found in POST, we attempt to pull data from the session variable (in the event this page has already been completed)
  //if no data is present, the fields remain empty
  $purchases = $_SESSION["userData"]["purchases"];

  //display section for each purchase
  foreach ($purchases as $key=>$value) {
    $satisfaction = "";
    if (isset($_POST["satisfaction"][$key])) {
      $satisfaction = $_POST["satisfaction"][$key];  
    } else if (isset($_SESSION["userData"]["purchases"][$key]["satisfaction"])) {
      $satisfaction = $_SESSION["userData"]["purchases"][$key]["satisfaction"];
    }
  
    $recommend = "";
    if (isset($_POST["recommend"][$key])) {
      $recommend = $_POST["recommend"][$key];  
    } else if (isset($_SESSION["userData"]["purchases"][$key]["recommend"])) {
      $recommend = $_SESSION["userData"]["purchases"][$key]["recommend"];
    }

    ?>

    <div class="item">

    <p><strong>Please answer the following questions regarding the <?php echo $key ?> you purchased from us</strong></p>

    <?php 
      //display errors if any exist for satisfaction field
      if (isset($errors[$key]["satisfaction"])) {
      ?><div class="error"><?php echo $errors[$key]["satisfaction"]?></div>
      <?php
    }?>

    <p>How happy are you with this device on a scale from 1 (not satisfied) to 5 (very satisfied)?</p>  
   
    <label><input type="radio" value="1" name="satisfaction[<?php echo $key?>]" <?php echo $satisfaction == "1" ? "checked" : ""?>>1</label>
    <label><input type="radio" value="2" name="satisfaction[<?php echo $key?>]" <?php echo $satisfaction == "2" ? "checked" : ""?>>2</label>
    <label><input type="radio" value="3" name="satisfaction[<?php echo $key?>]" <?php echo $satisfaction == "3" ? "checked" : ""?>>3</label>
    <label><input type="radio" value="4" name="satisfaction[<?php echo $key?>]" <?php echo $satisfaction == "4" ? "checked" : ""?>>4</label>
    <label><input type="radio" value="5" name="satisfaction[<?php echo $key?>]" <?php echo $satisfaction == "5" ? "checked" : ""?>>5</label>

    <br>
    <br>

    <?php 
      //display errors if any exist for recommend field
      if (isset($errors[$key]["recommend"])) {
      ?><div class="error"><?php echo $errors[$key]["recommend"]?></div>
      <?php
    }?>

    <p>Would you recommend the purchase of this device to a friend?</p>  

    <select name="recommend[<?php echo $key?>]">
      <option value="" <?php echo $recommend == "" ? "selected" : ""?>></option>
      <option value="Yes" <?php echo $recommend == "Yes" ? "selected" : ""?>>Yes</option>
      <option value="No" <?php echo $recommend == "No" ? "selected" : ""?>>No</option>
    </select>

    <br>
    </div>
    <?php
  }//end for loop
  ?>
    <!--if we are in edit mode, the text for the button should read "Submit Changes" instead of "next"-->
    <input class="button buttonright" type="submit" value="<?php echo (isset($_GET["edit"]) && $_GET["edit"] == "true") ? "Submit Changes" : "Next" ?>">
  </form>
<?php
}//end generateForm

?>

<button class="button buttonleft" onclick="location.href='page2.php'">Back</button>

<div id="clearfix"></div>
</div>
</body>
</html>
