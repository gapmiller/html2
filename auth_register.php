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

	session_start();
	include 'config.php';

	$username=$_POST['username'];
	$password=$_POST['password'];
	$password2=$_POST['password2'];
	$email = $_POST['email'];
    $action = $_POST['submit'];

    /**********************************
    *   Login
    **********************************/
    if ($action == "Login") {
        // get stored password hash
        $query = pg_exec($db,"SELECT * FROM tblUsers WHERE fldusername = '$username'") or die(pg_last_error());
        $data = pg_fetch_array($query);
        // check username and password
        if (password_verify ($password, $data['fldpassword'])) {
            // set session variables and return to home page with message
            $_SESSION['loggedin'] = 1;
            $_SESSION['logintime'] = idate("U");
            $_SESSION['active'] = $data['fldactive'];
            //$tPhptime = date('Y-m-d H:i:s');
            $qry = "UPDATE tblUsers SET fldlastlogin = now() WHERE fldusername = '$username'";
            $pgqry = pg_query($db, $qry);
            if ($data['fldactive'] == "f"){
                $_SESSION['message1'] = "Account: "  . $data['fldusername'] . " - Your account is not active. 
                    Contact the database administrator to confirm your registration.";
            }else{
                $_SESSION['message1'] = $data['fldusername'] . " is logged in.";
            }
            header("Location: index.php");

        }else{
            // message if login fails and return to authentication and login page
            $_SESSION['message1 = ""'];
            $_SESSION['message3'] = "Incorrect username or password.";
            header("Location: auth_register_form.php");
        }

    /**********************************
    *   Register
    **********************************/
    }else if ($action == "Register"){
        // Do passwords match?
		if ($password != $password2){
            $_SESSION['message2'] = "Passwords do not match. Please try again.";
			header("Location: auth_register_form.php");
        }else{
        	//Has the username or email been used?
            $checkuser = pg_exec($db, "SELECT fldusername FROM tblUsers WHERE fldusername='$username'");
            $username_exist = pg_num_rows($checkuser);
            $checkemail = pg_exec("SELECT fldEmail FROM tblUsers WHERE fldEmail='$email'");
            $email_exist = pg_num_rows($checkemail);
            if ($email_exist||$username_exist) {
                $_SESSION['message2'] = "Username or email exists.";
                header("Location: auth_register_form.php?authreg=register");
            }else{
                //Everything seems good, lets insert.
                $hpassword = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO tblUsers (fldusername, fldpassword, fldEmail) VALUES('$username','$hpassword','$email')";
                pg_exec($query) or die(pg_last_error());
                header("Location: auth_register_form.php");
                $_SESSION['message2'] = "Contact the database administrator to confirm your registration.";
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