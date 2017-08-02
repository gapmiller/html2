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

    //This will need to be redone when the code for user permissions is created
    //For now the only user that can do administrative functions is gretchnm
    if ($_SESSION['user']==="gretchnm") {
        $action = $_POST['submit'];

        // SQL to create the prepared statements
        $userQryResult = pg_prepare($db, "pst_user", 'SELECT * FROM tblUsers WHERE fldusername = $1');
        $updateUserQryUpdate = pg_prepare($db, "pst_update", 'UPDATE tblUsers SET fldpassword = $1 where fldusername = $2');

        $username=$_POST['username'];
        $password=$_POST['password'];
        $password2=$_POST['password2'];

        // Do passwords match?
        if ($password != $password2){
            $_SESSION['message2'] = "Passwords do not match. Please try again.";
            header("Location: auth_reset_form.php");
        }else{
        //Everything seems good, lets change the password if the user exists.
            $userQryResult = pg_execute($db, "pst_user", array($username));
            $checkuser = pg_fetch_all($userQryResult);
            $username_exist = sizeof($checkuser[0]['fldusername']);
            if ($username_exist) {
                $hpassword = password_hash($password, PASSWORD_DEFAULT);
                $updateUserQryUpdate = pg_execute($db, "pst_update", array($hpassword, $username));
                $_SESSION['message2'] = "Success! Password changed.";
                header("Location: auth_reset_form.php");
            }else{
            $_SESSION['message2'] = "User does not exist.";
            header("Location: auth_reset_form.php");
            }
        }
    }
?>