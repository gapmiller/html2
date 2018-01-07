<?php include('header.php'); ?>
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
    echo "<div class='siteinfo'>";

// functions

    // combines first and last name from database if there is one
    function Getname ($personid, $db) {
      $fullname = " no data";
      if ($personid !==NULL) { 
        $recPeople = pg_query($db, 'SELECT fldfirstname, fldlastname FROM tblpeople WHERE id = ' . $personid);
        $arrayPeople = pg_fetch_assoc($recPeople);
        if ($arrayPeople){
          $fullname = " " . $arrayPeople['fldfirstname'] . " ". $arrayPeople["fldlastname"];  
        }
      }
      return $fullname;
    } // end of function Getname

// beginning of content
      $sitenum = filter_input(INPUT_GET, 'num', FILTER_VALIDATE_INT); // sufficiently protected against SQL injection?
      
      if($sitenum==NULL){
        echo "That is not a valid site request.";
      }else{
        //connect to database
        include 'config.php';

        // spacer linefeed
        echo "<p>";

        // query for site info
        // need to changes query to a JOIN query and clean up the rest
        $recSites = pg_query($db, 'SELECT * FROM tblsites WHERE id =' . $sitenum); 
        $arraySites = pg_fetch_assoc($recSites);
        echo nl2br('<p class="title">'. $arraySites["fldsitename"] . '</p>' . "\n");

        echo "<p>";
        if (!is_null($arraySites["fldsiteaddress1"])){
          echo nl2br($arraySites["fldsiteaddress1"] . "\n");
        }

        if (!is_null($arraySites["fldsiteaddress2"])){
          echo nl2br($arraySites["fldsiteaddress2"] . "\n");
        }
         
        if (!is_null($arraySites["fldlocation"])){
          $qry = 'SELECT fldcity, fldstate, fldcountry FROM tblcities WHERE id = '.$arraySites["fldlocation"];
          $recCity = pg_query($db, $qry);
          $arrayCity = pg_fetch_assoc($recCity);
          echo nl2br($arrayCity["fldcity"] . ", " . $arrayCity["fldstate"] . " ");
        }
        print_r($arraySites["fldzipcode"]);

        if (!is_null($arraySites["fldlocation"]) || !is_null($arraySites["fldzipcode"])){
          echo "<br/>";
        }

        if (!is_null($arraySites["fldphone"])){
          echo nl2br($arraySites["fldphone"] . "\n");
        }
        
        if (!is_null($arraySites["fldsitetype"])){
          if ($arraySites["fldsitetype"] == "0"){
            echo "Unknown site type.";
          } else {
            $qry = 'SELECT fldsitetype, fldbmsmanufacturer FROM tblsitetypes WHERE id = '. $arraySites["fldsitetype"];
            $recType = pg_query($db, $qry);
            $arrayType = pg_fetch_assoc($recType);
            echo nl2br($arrayType["fldbmsmanufacturer"] . " " . $arrayType["fldsitetype"]);
            if (!is_null($arraySites["fldsoftwarever"])){
              echo ", version ";
              print_r($arraySites["fldsoftwarever"]);
            }
          }
          echo "<br/>";
        }

        if (!is_null($arraySites["fldnotes"])){
          echo "Notes: ";
          print_r($arraySites["fldnotes"]);
          echo "<br/>";
        }

        if (!is_null($arraySites["fldownerdialin"])){
          echo "Owner dials into site? ";
          if($arraySites["fldownerdialin"] === "t") {
            echo "Yes";
          }else{
            echo "No";
          }
          echo "<br/>";
        }
          
        if (!is_null($arraySites["fldacctmngrid"])){
          $qryRole = 'SELECT fldperson FROM tblfunction WHERE id = '. $arraySites["fldacctmngrid"];
          $recPerson = pg_query($db, $qryRole);
          $numPerson = pg_fetch_result($recPerson, 0, 0);
          $qryName = 'SELECT fldfirstname, fldlastname FROM tblpeople WHERE id = '. intval($numPerson);
          $recAcctMangr = pg_query($db, $qryName);
          $arrayAcctMangr = pg_fetch_assoc($recAcctMangr);
          echo "Account Manager: ";
          print_r($arrayAcctMangr["fldfirstname"]);
          echo " ";
          print_r($arrayAcctMangr["fldlastname"]);
          echo "<br/>";
        }

        if (!is_null($arraySites["fldremote"])){
          echo "Remote Connection: ";
          print_r($arraySites["fldremote"]);
          echo "<br/>";
        }

        if (!is_null($arraySites["fldbackup"])){
          $qry = 'SELECT fldfolder FROM tblbackup WHERE id = '. $arraySites["fldbackup"];
          $recBackup = pg_query($db, $qry);
          $arrayBackup = pg_fetch_assoc($recBackup);
          echo "Location of backup: ";
          echo nl2br($arrayBackup["fldfolder"] . "\n");
        }

        echo "</p>";
        echo "</div>";

        //table column headers
        echo "<table id='jobs'>";
        echo "<tr><th>Job Number</th> <th>Job Name </th><th>Warranty Start </th><th>Warranty End </th>
            <th>Sales Person </th><th>Project Manager </th><th>Programmer </th><th>Lead Installer </th></tr>";

        // query for site jobs
        $qry = "SELECT * FROM tbljobnumbers WHERE fldsiteid = " . $sitenum . ' ORDER BY fldjobnumber ASC';
        $recJobs = pg_query($db, $qry);
        $arrayJobs = pg_fetch_all($recJobs);
        $key = "id";

        // verify there are jobs for site
        if ($recJobs) {
          //check if it has any job information
          foreach ($arrayJobs as $key => $job) {
            echo "<tr>";
            $salesman = Getname ($job["fldsalesman"], $db);
            $projectmanager = Getname ($job["fldprojectmanager"], $db);
            $engineer = Getname ($job["fldengineer"], $db);
            $leadinstaller = Getname ($job["fldleadinstaller"], $db);
            echo "<td>" . $job["fldjobnumber"] . "</td>";

            if ($job["fldjobname"]!= ""){
              echo "<td>" . $job["fldjobname"] .  "</td>";  
            }else{
              echo "<td>" . "no data" . "</td>";
            }

            if ($job["fldwarrstart"]!= ""){
              echo "<td>" . $job["fldwarrstart"] . "</td>";  
            }else{
              echo "<td>" . "no data" . "</td>";
            }

            if ($job["fldwarrend"]!= ""){
              echo "<td>" . $job["fldwarrend"] . "</td>";  
            }else{
              echo "<td>" . "no data" . "</td>";
            }

            echo "<td>" . $salesman . "</td>";
            echo "<td>" . $projectmanager . "</td>";
            echo "<td>" . $engineer . "</td>";
            echo "<td>" . $leadinstaller . "</td>";
            echo "</tr>";
        }
          // if job information exists, print it for each job on site
          unset($recJobs);

        }else{
           //if no job information exists, print message and die
          echo "No job information found for this site.";
        }
      }
      
      echo "</table>";
      pg_close($db);
        ?>
      </section>
    </div>
  <?php include('footer.php'); ?>