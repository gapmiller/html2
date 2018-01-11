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
?>

<div class='siteinfo'>
  <section>
  
  <?php
  
    include 'config.php';
    
    // PHP sanitize - not sure this will work
    //$searchString = filter_input(INPUT_POST, 'searchstring', FILTER_SANITIZE_SPECIAL_CHARS);
    $searchString=$_POST['searchstring']; 

    // need to add additional search sections for old site names and job numbers

    // prepare query to search sites
    $searchQryResult = pg_prepare($db, "pst_query",
              'SELECT tblsites.*, 
                  tblcities.fldcity, tblcities.fldstate
                  FROM tblsites 
                LEFT JOIN tblcities ON tblsites.fldlocation = tblcities.id 
                WHERE fldsitename LIKE $1
                  OR fldsiteaddress1 LIKE $1
                  OR fldsiteaddress2 LIKE $1
                  OR tblcities.fldcity LIKE $1
                  OR tblcities.fldstate LIKE $1
                  OR fldzipcode LIKE $1
                  OR fldnotes LIKE $1
                  OR fldphone LIKE $1
                ORDER BY fldsitename ASC');

    $searchQryResult = pg_execute($db, "pst_query", array("%" . $searchString . "%"));
    $searchArray = pg_fetch_all($searchQryResult);

    if ($searchArray) {
      $key = "id";
        if ($searchArray) {
          echo nl2br('<p class=title>Search results for "'. $searchString . '":</p>');
          //echo nl2br('Search results for "'. $searchString . '":' . "\n");
          foreach ($searchArray as $key => $jobnum) {
            echo "<p>";
            echo nl2br('<a href= "jobnumbers.php?num=' . $jobnum["id"].'" target="_blank">'. $jobnum["fldsitename"] . '</a>' . "\n");
            echo "</p>";
          }
        }
      } else {
        die("No result found for ". $searchString . "  " . pg_last_error());
      }
  ?>

  </section>
</div>
<?php include('footer.php'); ?>