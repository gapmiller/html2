<?php include('header.php'); ?>
<?php session_start(); ?>
<!-- This needs a lot of work. Only authorized users should be able to get to this form. -->
        <section id="hero">
            <div class='container'>
                <?php

                
                    echo '<form class="reg-form" action="auth_reset.php" method="POST">
                        <h1>Reset Password (administrative use only)</h1>
                        <p>
                            <label for="username">Username</label>
                            <input type="text" name="username" placeholder="Username" required>
                        </p>                
                        <p>
                            <label for="password">Password</label>
                            <input type="password" name="password" placeholder="Password" required> 
                        </p>
                        <p>
                            <label for="password2">Password</label>
                            <input type="password" name="password2" placeholder="Re-enter Password" required> 
                        </p>
                        <p>
                            <input type="submit" name="submit" value="Reset">
                        </p>
                        <h2>';
                            if (isset($_SESSION['message2'])){
                                echo $_SESSION['message2'];
                            }    
                        echo '</h2>';
                    echo '</form>';
                    ?>
            </div>
        </section>
        <div class="container">
            <section>
                <form action="auth_register_form.php" method="GET">
                          <p><button type="submit"  name="authreg" value="register">
                          <span>Go to Registration Form</span></button></p></form>

                <form action="auth_register_form.php" method="GET">
                          <p><button type="submit"  name="authreg" value="login">
                          <span>Go to Login Form</span></button></p></form>
            </section>
        </div>

  <?php include('footer.php'); ?>