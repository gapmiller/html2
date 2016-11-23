 <?php include('header.php'); ?>
	<?php session_start(); ?>
		<div class="container">
		    <section>
		        <?php
		            echo '<p class="title">';
		            if ($_SESSION['message1'] != NULL){
		                echo $_SESSION['message1'];
		              }else if ($_SESSION['loggedin'] != 1) {
		                echo '<a href="auth_register_form.php">Must login to see data.</a>';
		              }else{
		                echo "Something went wrong. Try logging in again.";
		              }
		            echo '</p>';
		            $_SESSION['message2'] = "";
		            $_SESSION['message3'] = "";
		        ?>
		    <h1>Hello, world! (from /var/www/btgctrls)</h1>
		    </section>
		</div>

    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  <?php include('footer.php'); ?>
