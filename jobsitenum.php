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
        <p class=title>Site List</p>
        <h1>Click on name to view jobs done on the site.</h1>
        <br>
        <?php 
        	include 'config.php';
          
          // This is safe, since $_POST is converted automatically <- not sure this is true. Subject
          // to injection attacks?
          $recSites = pg_query($db, 'SELECT * FROM tblsites ORDER BY fldsitename ASC');
          $arraySites = pg_fetch_all($recSites);
            
          $key = "id";
            if ($recSites) {
              foreach ($arraySites as $key => $site) {
                echo'<p><h1><a href= "jobnumbers.php?num=' . $site["id"].'">'. $site["fldsitename"] . '</a></h1></p>';
              }
                unset($site);
            } else {
                echo "<p>There is a problem retrieving the site information.</p>";
            }
          //pg_free_result($arraySites);
          pg_close($db);
        ?>
      </section>
    </div>

  <?php include('footer.php'); ?>