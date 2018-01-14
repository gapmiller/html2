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

<div class="container">
  <section>
  
  <?php
  
    include 'config.php';
    
    // PHP sanitize - not sure this will work
    //$searchString = filter_input(INPUT_POST, 'searchstring', FILTER_SANITIZE_SPECIAL_CHARS);
    $searchString=$_POST['searchstring']; 

    // need to add additional search sections for old site names and job numbers

    // prepare query to search sites
    $searchSites = pg_prepare($db, "pst_sites",
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

    // prepare query to search job numbers
    $searchJobs = pg_prepare($db, "pst_jobs",
              'SELECT tbljobnumbers.*, 
                  tblsites.fldsitename
                  FROM tbljobnumbers 
                LEFT JOIN tblsites ON tbljobnumbers.fldsiteid = tblsites.id 
                WHERE fldjobnumber LIKE $1
                  OR fldjobname LIKE $1
                  OR fldjobnotes LIKE $1
                ORDER BY fldjobnumber ASC');

    // prepare query to search old site names
    $searchOldNames = pg_prepare($db, "pst_oldnames",
              'SELECT tbloldnames.*, 
                tblsites.fldsitename
                FROM tbloldnames 
              LEFT JOIN tblsites ON tbloldnames.fldcurrentname = tblsites.id 
                WHERE fldoldname LIKE $1
                ORDER BY fldoldname ASC');

          // query for list of old names and current name
          $recOldNames = pg_query($db, 
            'SELECT tbloldnames.*, 
                tblsites.fldsitename
                FROM tbloldnames 
              LEFT JOIN tblsites ON tbloldnames.fldcurrentname = tblsites.id 
              ORDER BY fldoldname ASC');
          $arrayOldNames = pg_fetch_all($recOldNames);

    // search sites
    $searchQryResult = pg_execute($db, "pst_sites", array("%" . $searchString . "%"));
    $searchArray = pg_fetch_all($searchQryResult);

    echo ('<p>(Not what you are looking for? Search is case sensitive. Trying typing again with fewer characters or different capitalization.)</p>');

    if ($searchArray) {
      $key = "id";
        if ($searchArray) {
          echo nl2br('<p class=title>Search results for "'. $searchString . '" in job site list:</p>');
          //echo nl2br('Search results for "'. $searchString . '":' . "\n");
          foreach ($searchArray as $key => $jobnum) {
            echo "<p>";
            echo nl2br('<a href= "jobnumbers.php?num=' . $jobnum["id"].'" target="_blank">'. $jobnum["fldsitename"] . '</a>' . "\n");
            echo nl2br("</p>");
          }
        }
      } else {
        echo nl2br('<p class=title>No result found for "'. $searchString . '" in job site list. ' . pg_last_error() . '</p>');
      }

    // search job numbers
    $searchQryResult = pg_execute($db, "pst_jobs", array("%" . $searchString . "%"));
    $searchArray = pg_fetch_all($searchQryResult);

    if ($searchArray) {
      $key = "id";
        if ($searchArray) {
          echo nl2br('<p class=title>Search results for "'. $searchString . '" in job number list:</p>');

          echo "<table id='jobs'>";
          echo "<tr><th>Job Number</th> <th>Job Name </th> <th>Job Site </th> <th>Job Site </th></tr>";
          foreach ($searchArray as $key => $jobnum) {
                echo "<tr>";
                echo "<td>". $jobnum["fldjobnumber"] . "</td>";
                echo "<td>". $jobnum["fldjobname"] . "</td>";
                echo'<td><a href= "jobnumbers.php?num=' . $jobnum["fldsiteid"].'">'. $jobnum["fldsitename"] . '</a></td>';
                echo "<td>". $jobnum["fldjobnotes"] . "</td>";
                echo "</tr>";
          }
          echo nl2br("</table>");
        }
      } else {
        echo nl2br('<p class=title>No result found for "'. $searchString . '" in job number list. ' . pg_last_error() . '</p>');
      }

    // search old site names
    $searchQryResult = pg_execute($db, "pst_oldnames", array("%" . $searchString . "%"));
    $searchArray = pg_fetch_all($searchQryResult);

    if ($searchArray) {
      $key = "id";
        if ($searchArray) {
          echo nl2br('<p class=title>Search results for "'. $searchString . '" in old site name list:</p>');

          echo "<table id='oldnames'>";
          echo "<tr><th>Alternate or Previous Name</th> <th>Current Site Name </th></tr>";
          foreach ($searchArray as $key => $jobnum) {
                echo "<tr>";
                echo "<td>". $jobnum["fldoldname"] . "</td>";
                echo'<td><a href= "jobnumbers.php?num=' . $jobnum["fldsiteid"].'">'. $jobnum["fldsitename"] . '</a></td>';
                echo "</tr>";
          }
          echo nl2br("</table>");
        }
      } else {
        echo nl2br('<p class=title>No result found for "'. $searchString . '" in job number list. ' . pg_last_error() . '</p>');
      }
  ?>

  </section>
</div>
<?php include('footer.php'); ?>