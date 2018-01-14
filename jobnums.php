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
  <div class="container">
    <section>
        <?php 

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
          
          include 'config.php';
          echo "<p class='title'>Job Number List</p>";
          echo "<table id='jobs'>";
          echo "<tr><th>Job Number</th> <th>Job Name </th> <th>Job Site </th> <th>Notes</th>
            <th>Sales Person </th><th>Project Manager </th><th>Programmer </th><th>Lead Installer </th></tr>";

          $recJobs = pg_query($db, 
            'SELECT tbljobnumbers.*, 
                tblsites.fldsitename
                FROM tbljobnumbers 
              LEFT JOIN tblsites ON tbljobnumbers.fldsiteid = tblsites.id
              ORDER BY fldjobnumber ASC');
          $arrayJobnums = pg_fetch_all($recJobs);

          $key = "id";
            if ($recJobs) {
              foreach ($arrayJobnums as $key => $jobnum) {
                echo "<tr>";
                echo "<td>". $jobnum["fldjobnumber"] . "</td>";
                echo "<td>". $jobnum["fldjobname"] . "</td>";
                echo'<td><a href= "jobnumbers.php?num=' . $jobnum["fldsiteid"].'">'. $jobnum["fldsitename"] . '</a> </td>';
                echo "<td>". $jobnum["fldjobnotes"] . "</td>";

                $salesman = Getname ($jobnum["fldsalesman"], $db);
                $projectmanager = Getname ($jobnum["fldprojectmanager"], $db);
                $engineer = Getname ($jobnum["fldengineer"], $db);
                $leadinstaller = Getname ($jobnum["fldleadinstaller"], $db);
                
                echo "<td>" . $salesman . "</td>";
                echo "<td>" . $projectmanager . "</td>";
                echo "<td>" . $engineer . "</td>";
                echo "<td>" . $leadinstaller . "</td>";
                echo "</tr>";
              }
              echo "</table>";
              unset($jobnum);
            } else {
                echo nl2br ("There is a problem retrieving the job information.\n");
            }

          pg_close($db);

        ?>
      </section>
    </div>
    
  <?php include('footer.php'); ?>