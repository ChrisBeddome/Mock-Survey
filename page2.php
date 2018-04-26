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
//This page asks the user for the purchase method and products purchased
//Upon submission, the same page is served, but with the "mode" GET variable set to "submit"
//This page can also be served with the "edit" GET variable set to true, this occurs when the user is editing an answer from the review table at the end of the survey

$page = 2;
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

  //purchase method must be specified
  if (!isset($_POST["howPurchased"])) {
    $errors["howPurchased"] = "Must select an option";
  }

  //must select at least one item
  if (!isset($_POST["purchases"])) {
    $errors["purchases"] = "Must select at least one item";
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
    //reset purchases SESSION variable so form does not pull old data
    unset($_SESSION["userData"]["purchases"]);
    generateForm($errors);
  } else {
    submitForm();
  }
}

//updates the session data variables and the session page progress variable, redirects to next page
function submitForm() {
  $howPurchased = $_POST["howPurchased"];
  $purchases = $_POST["purchases"];

  $_SESSION["userData"]["howPurchased"] = $howPurchased;
  
  //if item has been selected that is not already in session array, add the item
  //cannot simply delete and repopulate array upon submission because we would lose "satisfaction" and "recommend" values for those items if already submitted
  foreach($purchases as $item) {
    if (!array_key_exists($item, $_SESSION["userData"]["purchases"])) {
      $_SESSION["userData"]["purchases"][$item] = [];
      
      //if we are in edit mode, remove edit mode
      //Adding a new item to the array results in us having to enter additional information about that item on the next page, So we cannot simply "submit changes" and return to results
      unset($_GET["edit"]);
    }
  }

  //remove old purchases from session variable if they are not checked in current submission
  //cannot simply delete and repopulate array upon submission because we would lose "satisfaction" and "recommend" values for those items if already submitted
  foreach ($_SESSION["userData"]["purchases"] as $item => $value) {
    if (!in_array($item, $purchases)) {
      unset($_SESSION["userData"]["purchases"][$item]);
    }
  }

  //if this is the furthest the user has gotten in the survey, set the progress to this page
  if ($_SESSION["progress"] < $_SESSION["page"]) {
    $_SESSION["progress"] = $_SESSION["page"];
  }

  //if in edit mode, jump back to the edit table, else go to next page
  if (isset($_GET["edit"]) && $_GET["edit"] == "true") {
    header("location: page4.php");
  } else {
    header("location: page3.php");
  }
}

function generateForm($errors) {

  //The following variables attempt to pull data from the POST variable (in the event of an unvalidated submission)
  //If no data is found in POST, we attempt to pull data from the session variable (in the event this page has already been completed)
  //if no data is present, the fields remain empty
  $howPurchased = "";
  if (isset($_POST["howPurchased"])) {
    $howPurchased = $_POST["howPurchased"];  
  } else if (isset($_SESSION["userData"]["howPurchased"])) {
    $howPurchased = $_SESSION["userData"]["howPurchased"];
  }

  $purchases = [];
  if (isset($_POST["purchases"])) {
    $purchases = $_POST["purchases"];  
  } else if (isset($_SESSION["userData"]["purchases"])) {
    $purchases = $_SESSION["userData"]["purchases"];
  }

  ?>
  <form id="form2" action="page2.php?mode=submit<?php echo (isset($_GET["edit"]) && $_GET["edit"] == "true") ? "&edit=true" : ""?>" method="post">

    <?php 
      //display errors if any exist for purchase method field
      if (isset($errors["howPurchased"])) {
      ?><div class="error"><?php echo $errors["howPurchased"]?></div>
      <?php
    }?>

    <div><strong>How did you complete your purchase?</strong></div>  
    <br>
    <label><input type="radio" name="howPurchased" value="online" <?php echo $howPurchased == "online" ? "checked" : ""?>>Online</label>
    <label><input type="radio" name="howPurchased" value="phone" <?php echo $howPurchased == "phone" ? "checked" : ""?>>By Phone</label>
    <label><input type="radio" name="howPurchased" value="mobile" <?php echo $howPurchased == "mobile" ? "checked" : ""?>>Mobile App </label>
    <label><input type="radio" name="howPurchased" value="store" <?php echo $howPurchased == "store" ? "checked" : ""?>>In Store</label>

    <br />

    <?php 
      //display errors if any exist for purchases field
      if (isset($errors["purchases"])) {
      ?><div class="error"><?php echo $errors["purchases"]?></div>
      <?php
    }?>

    <div><strong>Which of the following products did you purchase?</strong></div> 
    <br>
    <label><input type="checkbox" name="purchases[]" value="Phone" <?php echo array_key_exists("Phone", $purchases) ? "checked" : ""?>>Phone</label>
    <label><input type="checkbox" name="purchases[]" value="Smart TV" <?php echo array_key_exists("Smart TV", $purchases) ? "checked" : ""?>>Smart TV</label>
    <label><input type="checkbox" name="purchases[]" value="Laptop" <?php echo array_key_exists("Laptop", $purchases) ? "checked" : ""?>>Laptop</label>
    <label><input type="checkbox" name="purchases[]" value="Tablet" <?php echo array_key_exists("Tablet", $purchases) ? "checked" : ""?>>Tablet</label>
    <label><input type="checkbox" name="purchases[]" value="Home Theater" <?php echo array_key_exists("Home Theater", $purchases) ? "checked" : ""?>>Home Theater</label>

    <br />

    <!--if we are in edit mode, the text for the button should read "Submit Changes" instead of "next"-->
    <input class="button buttonright" type="submit" value="<?php echo (isset($_GET["edit"]) && $_GET["edit"] == "true") ? "Submit Changes" : "Next" ?>">

  </form>
  <?php
}//end generateForm

?>

<button class="button buttonleft" onclick="location.href='page1.php'">Back</button>

<div id="clearfix"></div>
</div>
</body>
</html>
