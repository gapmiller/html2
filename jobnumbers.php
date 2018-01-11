<?php include('header.php'); ?>
<section id="hero">
</section> 
</header>
<?php
// if no session is set, go straight back to index
if (isset($_SESSION['loggedin'])){
  // if the user isn't allowed to see data, go back to the index
  if(($_SESSION['loggedin'] != 1) || ($_SESSION['active'] == "f")){
    header("Location: index.php");
    if (isset($_SESSION['message1'])){
        echo $_SESSION['message1'];
    }
    exit; 
  }
}else{
  header("Location: index.php");
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

        // query for site info
        $qry = 'SELECT tblsites.*, 
                  tblcities.fldcity, tblcities.fldstate, 
                  tblsitetypes.fldbmsmanufacturer, tblsitetypes.fldsitetype, 
                  tblpeople.fldfirstname, tblpeople.fldlastname, 
                  tblbackup.fldfolder, tblbackup.fldbaknotes
                FROM tblsites 
                LEFT JOIN tblcities ON tblsites.fldlocation = tblcities.id 
                LEFT JOIN tblsitetypes ON tblsites.fldsitetype = tblsitetypes.id
                LEFT JOIN tblfunction ON tblsites.fldacctmngrid = tblfunction.id
                LEFT JOIN tblpeople ON tblfunction.fldperson = tblpeople.id
                LEFT JOIN tblbackup ON tblsites.fldbackup = tblbackup.id
                WHERE tblsites.id = ' . $sitenum;

                $recSites = pg_query($db, $qry);
                
        // site title
        $site = pg_fetch_assoc($recSites);
        echo nl2br('<p class="title">'. $site["fldsitename"] . '</p>' . "\n");

        // Should this be turned into a function? Also used in jobsites.php
        // beginning of site info
        echo "<p>";

                // up to two lines of address if known
                if (!is_null($site["fldsiteaddress1"])){
                  echo nl2br($site["fldsiteaddress1"] . "\n");
                }
                if (!is_null($site["fldsiteaddress2"])){
                  echo nl2br($site["fldsiteaddress2"] . "\n");
                }
                
                // city and state if known
                if (!is_null($site["fldlocation"])){
                  echo nl2br($site["fldcity"] . ", " . $site["fldstate"] . " ");
                }
                
                // zip code
                // if it's NULL, nothing prints and that doesn't cause problems so no check for NULL required
                echo nl2br($site["fldzipcode"]);
                // don't print a line feed if nothing printed for zip code
                if (!is_null($site["fldlocation"]) || !is_null($site["fldzipcode"])){
                  echo "<br/>";
                }

                // phone number if known
                if (!is_null($site["fldphone"])){
                  echo nl2br($site["fldphone"]  . "\n");
                }

                // site type and software info if known
                if (!is_null($site["fldsitetype"])){
                  if ($site["fldsitetype"] == "0"){
                    echo "Unknown site type.";
                  } else {
                    echo nl2br($site["fldbmsmanufacturer"] . " " . $site["fldsitetype"]);
                    if (!is_null($site["fldsoftwarever"])){
                      echo nl2br(", version " . $site["fldsoftwarever"]);
                    }            
                  }
                  echo "<br/>";
                }

                /*
                This field isn't currently being used.
                if (!is_null($site["fldglobalcontroller"])){
                  echo nl2br($site["fldbmsmanufacturer"]);
                  echo nl2br($site["fldglobalcontroller"]);
                  echo "<br/>";
                }
                */

                // notes if any
                if (!is_null($site["fldnotes"])){
                  echo nl2br("Notes: " . $site["fldnotes"]  . "\n");
                }

                // flag if owner dials into site 
                if (!is_null($site["fldownerdialin"])){
                  echo "Owner dials into site? ";
                  if($site["fldownerdialin"] === "t") {
                    echo nl2br("Yes\n");
                  }else{
                    echo nl2br("No\n");
                  }
                }

                // account manager
                if (!is_null($site["fldacctmngrid"])){
                  echo nl2br("Account Manager: " . $site["fldfirstname"] . " " . $site["fldlastname"] . "\n");
                }

                // remote connection info if known
                if (!is_null($site["fldremote"])){
                  echo nl2br("Remote Connection: " . $site["fldremote"] . "\n");
                }

                // location of backup files if known
                if (!is_null($site["fldbackup"])){
                  if (!is_null($site["fldfolder"])){
                    echo nl2br("Location of backup: " . $site["fldfolder"] . "\n");
                  }
                  if (!is_null($site["fldbaknotes"])){
                    echo nl2br("Backup notes: " . $site["fldbaknotes"] . "\n");
                  }
                }

                echo "<br/>";

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