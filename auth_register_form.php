

<?php include('header.php'); ?>
<?php session_start(); ?>

        <section id="hero">
            <div class='container'>
                <?php

                        $authreg=$_GET['authreg'];
                if ($authreg === 'register'){
                    echo '<form class="reg-form" action="auth_register.php" method="POST">
                        <h1>Register</h1>
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
                            <label for="email">Email</label>
                            <input type="email" name="email" placeholder="Email" required>
                        </p>
                        <p>
                            <input type="submit" name="submit" value="Register">
                        </p>
                        <h2>';
                            if ($_SESSION['message2'] != NULL){
                                echo $_SESSION['message2'];
                            }    
                        echo '</h2>';

                    echo '</form>';

                    $_SESSION['message3'] = "";

                }else{
                    echo '<form class="auth-form" action="auth_register.php" method="POST">
                        <h1>Login</h1>
                        <p>
                            <label for="username">Username</label>
                            <input type="text" name="username" placeholder="Username" required>
                        </p>                
                        <p>
                            <label for="password">Password</label>
                            <input type="password" name="password" placeholder="Password" required> 
                        <p>
                            <input type="submit" name="submit" value="Login">
                        </p>
                        <h2>';


                    $_SESSION['message2'] = "";
                        
                    if ($_SESSION['message1'] != NULL){
                        echo $_SESSION['message1'];
                    }

                    if ($_SESSION['message3'] != NULL){
                        echo $_SESSION['message3'];
                    }
                    echo '</h2>';
                    echo '</form>';
                }
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