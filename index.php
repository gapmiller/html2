<?php include('header.php'); ?>    
    <section id="hero">
    </section> 
    </header>
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
        </section>
    </div>
<?php include('footer.php'); ?>