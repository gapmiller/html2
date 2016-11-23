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
        <p class=title>Alternate and Previous Site Names</p>
        <?php 
    	    //connect to database
    	    include 'config.php';
          echo "<table id='oldnames'>";
          echo "<tr><th>Alternate or Previous Name</th> <th>Current Site Name </th></tr>";

          // query for old name info
          $recOldNames = pg_query($db, 'SELECT * FROM tbloldnames *ORDER BY fldoldname ASC');
          $arrayOldNames = pg_fetch_all($recOldNames);
          $key = "id";

          //fill out table
          foreach ($arrayOldNames as $key => $oldname) {
            $sitenum = $oldname["fldcurrentname"];
            $recSites = pg_query($db, 'SELECT fldsitename FROM tblsites WHERE id =' . $sitenum);
            $arraySites = pg_fetch_assoc($recSites);
            $sitename = $arraySites["fldsitename"];
            //echo nl2br($oldname["fldoldname"] . " - " .  
            // '<a href= "jobnumbers.php?num=' . $sitenum .'">'. $sitename . '</a>' . "\n");

            echo '<tr><td>'. $oldname["fldoldname"] . '</td>' . 
              '<td><a href= "jobnumbers.php?num=' . $sitenum .'">'. $sitename . '</a></td></tr>';
          }
          echo "</table>";
        ?>
      </section>
    </div>

  <?php include('footer.php'); ?>