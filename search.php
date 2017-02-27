<?php 
    /*
    *   Search Database
    *   Gretchen Miller
    *   2/26/2017
    *
    *   File name: search.php
    *   Files that depend on header.php:
    *       Has the serch box that appears on all pages.
    *   Files that this file depends on:
    *       config.php  - connects to database
    *       header.php
    *
    *   Future
    *   - Break search results into pages instead of dumping everthing into a single page.
    */

include('header.php'); ?>
<section id="hero">
</section> 
</header>
<?php
if (($_SESSION['loggedin'] != 1) || ($_SESSION['active'] == "f")){
  header("Location: index.php");
  if ($_SESSION['message1'] != NULL){
      echo $_SESSION['message1'];
  }
  exit; 
}
  
  include 'config.php';
  $searchString=$_POST['searchstring'];
  $searchSql = "SELECT * FROM tblsites WHERE fldsitename LIKE '%$1%'";
//    OR fldsiteaddress1 LIKE %$1%
//    OR fldsiteaddress2 LIKE %$1%';

  if (!pg_prepare($searchstring, $searchSql)) {
    die("Invalid search options '$searchstring': " . pg_last_error());
  } else {
    $searchPrep = pg_prepare($searchstring, $searchSql);
    $searchArray = pg_fetch_all($searchPrep);
    $key = "id";
            if ($searchPrep) {
              foreach ($searchArray as $key => $jobnum) {
                echo "<tr>";
                echo "<td>". $jobnum["fldsitename"] . "</td>";
                echo "</tr>";
              }
  }

  echo "Search results for '" . $searchString , "':";
  echo $searchPrep(1);
  $reqSearch = pg_execute($searchstring, array(1));


?>