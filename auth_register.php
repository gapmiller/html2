<?php 
    /*
    *   Login and Registration
    *   Gretchen Miller
    *   5/20/2016
    *
    *   File name: auth_register.php
    *   Files that depend on auth_register.php:
    *       Provides the code for auth_register_form.php
    *       header.php - logout button
    *   Files that this file depends on:
    *       config.php  - connects to database
    *       auth_register_form.php
    *       index.php
    *
    *   Future
    *   - create function that all pages can reference to automatically log
            user out of session is too old.
    */
    // information about preventing SQL injection and using prepared queries
    // https://www.linkedin.com/pulse/protecting-your-postgresql-database-from-sql-attack-rogers-iii

    session_start();
    include 'config.php';

    $action = $_POST['submit'];

    // SQL to create the prepared statements
    $userQryResult = pg_prepare($db, "pst_user", 'SELECT * FROM tblUsers WHERE fldusername = $1');    
    $emailQryResult = pg_prepare($db, "pst_email", 'SELECT * FROM tblUsers WHERE fldemail = $1');
    $newUserQryUpdate = pg_prepare($db, "pst_new", 'INSERT INTO tblUsers (fldusername, fldpassword, fldemail) VALUES($1, $2, $3)');

    /**********************************
    *   Login
    **********************************/
    if ($action == "Login") {
            $username=$_POST['username'];
            $password=$_POST['password'];

        // get stored password hash
        // Execute the prepared statement to check user login
        $userQryResult = pg_execute($db, "pst_user", array($username));
        $data = pg_fetch_all($userQryResult);
        // check username and password
        if (password_verify ($password, $data[0]['fldpassword'])) {
            // set session variables and return to home page with message
            $_SESSION['loggedin'] = 1;
            $_SESSION['logintime'] = idate("U");
            $_SESSION['active'] = $data[0]['fldactive'];
            //$tPhptime = date('Y-m-d H:i:s');
            $qry = "UPDATE tblUsers SET fldlastlogin = now() WHERE fldusername = '" . $data[0]['fldusername'] ."'";
            $pgqry = pg_query($db, $qry);
            if ($data[0]['fldactive'] == "f"){
                $_SESSION['message1'] = "Account: "  . $data[0]['fldusername'] . " - Your account is not active. 
                    Contact the database administrator to confirm your registration.";
            }else{
                $_SESSION['message1'] = $data[0]['fldusername'] . " is logged in.";
            }
            header("Location: index.php");

        }else{
            // message if login fails and return to authentication and login page
            $_SESSION['message1'] = "";
            $_SESSION['message3'] = "Incorrect username or password.";
            header("Location: auth_register_form.php");
        }
    
    /**********************************
    *   Register
    **********************************/
    }else if ($action == "Register"){
            $username=$_POST['username'];
            $password=$_POST['password'];
            $password2=$_POST['password2'];
            $email = $_POST['email'];

        // Do passwords match?
        if ($password != $password2){
            $_SESSION['message2'] = "Passwords do not match. Please try again.";
            header("Location: auth_register_form.php");
        }else{
            //Has the username or email been used?
            $userQryResult = pg_execute($db, "pst_user", array($username));
            $checkuser = pg_fetch_all($userQryResult);
            $username_exist = sizeof($checkuser[0]['fldusername']);

            $emailQryResult = pg_execute($db, "pst_email", array($email));
            $checkemail = pg_fetch_all($emailQryResult);
            $email_exist = sizeof($checkemail[0]['fldemail']);

            //if either exist, error; otherwise add
            if ($username_exist||$email_exist) {
            //if ($userQryResult||$emailQryResult) {
                $_SESSION['message2'] = "Username or email exists.";
                header("Location: auth_register_form.php?authreg=register");

            // add user
            }else{
                //Everything seems good, lets insert.
                $hpassword = password_hash($password, PASSWORD_DEFAULT);
                $newUserQryUpdate = pg_execute($db, "pst_new", array($username, $hpassword, $email));
                //$query = "INSERT INTO tblUsers (fldusername, fldpassword, fldemail) VALUES('$username','$hpassword','$email')";
                //pg_exec($query) or die(pg_last_error());
                $_SESSION['message3'] = "Success! Contact database administrator to confirm registration.";
                header("Location: auth_register_form.php");
            }
        } 


    /**********************************
    *   Logout
    **********************************/       
    }else if ($action =="Logout"){
        session_unset();
        session_destroy();
        header("Location: auth_register_form.php");

    /**********************************
    *   All else
    **********************************/
    }else{
        $_SESSION['message1'] = "So confused! What did you want to do?";
        $_SESSION['message2'] = NULL;
        header("Location: auth_register_form.php");
    }
?>