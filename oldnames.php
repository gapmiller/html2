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
        <p class=title>Alternate and Previous Site Names</p>
        <?php 
    	    //connect to database
    	    include 'config.php';
          echo "<table id='oldnames'>";
          echo "<tr><th>Alternate or Previous Name</th> <th>Current Site Name </th></tr>";

          // query for list of old names and current name
          $recOldNames = pg_query($db, 
            'SELECT tbloldnames.*, 
                tblsites.fldsitename
                FROM tbloldnames 
              LEFT JOIN tblsites ON tbloldnames.fldcurrentname = tblsites.id 
              ORDER BY fldoldname ASC');
          $arrayOldNames = pg_fetch_all($recOldNames);

          //fill out table
          $key = "id";
          foreach ($arrayOldNames as $key => $oldname) {
            $sitename = $oldname["fldsitename"];
            echo '<tr><td>'. $oldname["fldoldname"] . '</td>' . 
              '<td><a href= "jobnumbers.php?num=' . $oldname["fldcurrentname"] .'">'. $sitename . '</a></td></tr>';
          }
          echo "</table>";

          pg_close($db);

        ?>
      </section>
    </div>

  <?php include('footer.php'); ?>
  