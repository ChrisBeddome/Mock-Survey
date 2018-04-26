<?php
//Name: Chris Beddome
//StudenNumber: 0810580

session_start();

//if user has already visited site, load the last visited page
if (isset($_SESSION["page"])) {
  loadLastPage();
} else { //else initialize session data
  //stores user answers
  $_SESSION["userData"] = [];
  //stores completed page index. If page 2 has been completed, progress will = 2.
  //initialize to -1 because page0 is a page that can be completed.
  $_SESSION["progress"] = -1;
  
  //redirect to intro page
  header("location: page0.php");
}

//loads the last page visited by the user
function loadLastPage() {
  $page = $_SESSION["page"];

  header("location: page$page.php");
}

?>

