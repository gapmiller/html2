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
  <div class="container">
    <section>
        <?php 
           
          include 'config.php';
          echo "<p class='title'>Job Number List</p>";
          echo "<table id='jobs'>";
          echo "<tr><th>Job Number</th> <th>Job Name </th></tr>";

          $recJobs = pg_query($db, 'SELECT * FROM tbljobnumbers ORDER BY fldjobnumber ASC');
          $arrayJobnums = pg_fetch_all($recJobs);
          $key = "id";
            if ($recJobs) {
              foreach ($arrayJobnums as $key => $jobnum) {
                echo "<tr>";
                echo "<td>". $jobnum["fldjobnumber"] . "</td>";
                echo "<td>". $jobnum["fldjobname"] . "</td>";
                echo "</tr>";
              }
                unset($jobnum);
            } else {
                echo nl2br ("There is a problem retrieving the job information.\n");
            }

          pg_close($db);

        ?>
      </section>
    </div>
    
  <?php include('footer.php'); ?>