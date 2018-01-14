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
                tblpeople.fldfirstname, tblpeople.fldlastname, 
                tblbackup.fldfolder, tblbackup.fldbaknotes
                FROM tblsites 
              LEFT JOIN tblcities ON tblsites.fldlocation = tblcities.id 
              LEFT JOIN tblsitetypes ON tblsites.fldsitetype = tblsitetypes.id
              LEFT JOIN tblfunction ON tblsites.fldacctmngrid = tblfunction.id
              LEFT JOIN tblpeople ON tblfunction.fldperson = tblpeople.id
              LEFT JOIN tblbackup ON tblsites.fldbackup = tblbackup.id
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