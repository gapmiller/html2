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

<!--content for page -->
  <div class="bodycontainer">
    <section>
        <p class=title>Site Information</p>
        <h1>Click on name to view jobs done on the site.</h1>
        <br>

        <?php 
	       include 'config.php';
          $recSites = pg_query($db, 
            'SELECT tblsites.*, 
                tblcities.fldcity, tblcities.fldstate, 
                tblsitetypes.fldbmsmanufacturer, tblsitetypes.fldsitetype, 
                tblpeople.fldfirstname, tblpeople.fldlastname 
                FROM tblsites 
              LEFT JOIN tblcities ON tblsites.fldlocation = tblcities.id 
              LEFT JOIN tblsitetypes ON tblsites.fldsitetype = tblsitetypes.id
              LEFT JOIN tblfunction ON tblsites.fldacctmngrid = tblfunction.id
              LEFT JOIN tblpeople ON tblfunction.fldperson = tblpeople.id
              ORDER BY fldsitename ASC');
          $arraySites = pg_fetch_all($recSites);
            
          // the only field that is guaranteed to have data is fldsitename. All others can be NULL.
          $key = "id";
            if ($recSites) {
              foreach ($arraySites as $key => $site) {
                // site name with link
                echo'<a href= "jobnumbers.php?num=' . $site["id"].'">'. $site["fldsitename"] . '</a>';
                echo "<br/>";

                // up to two lines of address if known
                if (!is_null($site["fldsiteaddress1"])){
                  print_r($site["fldsiteaddress1"]);
                  echo "<br/>";
                }
                if (!is_null($site["fldsiteaddress2"])){
                  print_r($site["fldsiteaddress2"]);
                  echo "<br/>";
                }
                
                // city and state if known
                if (!is_null($site["fldlocation"])){
                  print_r($site["fldcity"] . ", " . $site["fldstate"] . " ");
                }
                
                // zip code
                // if it's NULL, nothing prints and that doesn't cause problems so no check for NULL required
                print_r($site["fldzipcode"]);
                // don't print a line feed if nothing printed for zip code
                if (!is_null($site["fldlocation"]) || !is_null($site["fldzipcode"])){
                	echo "<br/>";
                }

                // phone number if known
                if (!is_null($site["fldphone"])){
                  print_r($site["fldphone"]);
                  echo "<br/>";
                }

                // site type and software info if known
                if (!is_null($site["fldsitetype"])){
                  if ($site["fldsitetype"] == "0"){
                    echo "Unknown site type.";
                  } else {
                    print_r($site["fldbmsmanufacturer"]);
                    echo " ";
                    print_r($site["fldsitetype"]);
                    if (!is_null($site["fldsoftwarever"])){
                      print_r(", version " . $site["fldsoftwarever"]);
                    }            
                  }
                  echo "<br/>";
                }

                /*
                This field isn't currently being used.
                if (!is_null($site["fldglobalcontroller"])){
                  $qry = 'SELECT fldsitetype, fldbmsmanufacturer FROM tblsitetypes WHERE id = '. $site["fldsitetype"];
                  $recType = pg_query($db, $qry);
                  $arrayType = pg_fetch_assoc($recType);
                  print_r($arrayType["fldbmsmanufacturer"]);
                  print_r($site["fldglobalcontroller"]);
                  echo "<br/>";
                }
                */

                // notes if any
                if (!is_null($site["fldnotes"])){
                  echo "Notes: ";
                  print_r($site["fldnotes"]);
                  echo "<br/>";
                }

                // flag if owner dials into site 
                if (!is_null($site["fldownerdialin"])){
                  echo "Owner dials into site? ";
                  if($site["fldownerdialin"] === "t") {
                    echo "Yes";
                  }else{
                    echo "No";
                  }
                  echo "<br/>";
                }

                // account manager
                if (!is_null($site["fldacctmngrid"])){
                  echo nl2br("Account Manager: " . $site["fldfirstname"] . " " . $site["fldlastname"] . "\n");
                }

                // remote connection info if known
                if (!is_null($site["fldremote"])){
                  echo "Remote Connection: ";
                  print_r($site["fldremote"]);
                  echo "<br/>";
                }

                // location of backup files if known
                if (!is_null($site["fldbackup"])){
                  $qry = 'SELECT fldfolder FROM tblbackup WHERE id = '. $site["fldbackup"];
                  $recBackup = pg_query($db, $qry);
                  $arrayBackup = pg_fetch_assoc($recBackup);
                  echo "Location of backup: ";
                  echo nl2br($arrayBackup["fldfolder"] . "\n");
                }

              	echo "<br/>";

              } // closes foreach ($arraySites as $key => $site)

              // cleanup when done with this record
              //pg_free_result($recSites);
              unset($site);
            } else {
                // error message if something goes wrong selecting site data
                echo "There is a problem retrieving the site information.\n";
            } // closes "if ($recSites)"

          // cleanup when done
            pg_close($db);

        ?>
      </section>
    </div>

  <?php include('footer.php'); ?>