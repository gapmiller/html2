<?php include('header.php'); ?>    
    <section id="hero">
    </section> 
    </header>
    <div class="container">
        <section>
            <?php
                echo '<p class="title">';
                if (isset($_SESSION['message1'])){
                    if ($_SESSION['message1'] != ""){
                        echo $_SESSION['message1'];
                    }
                }
                if (isset($_SESSION['loggedin'])){
                    if($_SESSION['loggedin'] != 1) {
                        echo '<a href="auth_register_form.php">Must login to see data.</a>';
                    }
                }else if (!isset($_SESSION['loggedin'])){
                    echo '<a href="auth_register_form.php">Must login to see data.</a>';
                }
                echo '</p>';
                $_SESSION['message2'] = "";
                $_SESSION['message3'] = "";
            ?>
        </section>
    </div>
<?php include('footer.php'); ?>

