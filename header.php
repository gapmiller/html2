<?php
// Start the session - this has to come first per http://www.w3schools.com/php/php_sessions.asp
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" /> <!-- Triggers responsive -->
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Climatec Controls</title>

    <!-- Styles -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- icon for browser tab -->
    <link rel="icon" href="favicon-16x16.png">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>
    <body>
      <header id="masthead">
       <nav class="navbar navbar-default">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" id="brand" href="/">Job Numbers and Sites</a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
              <li><a href="oldnames.php">Alternate Site Names</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Site List <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="jobsitenum.php">Site Names</a></li>
                  <li><a href="jobsites.php">Site and Information</a></li>
                </ul>
              </li>
            </ul>

            <!-- search box -->
            <form class="navbar-form navbar-left">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Search">
              </div>
              <button type="submit" class="btn btn-default">Submit</button>
            </form>

            <!-- login/register or logout -->
            <ul class="nav navbar-nav navbar-right">
              <?php
                  if ($_SESSION['loggedin'] === 1){
                      echo '<li><form action="auth_register.php" method="POST">
                          <p><button type="submit" class="navbutton" name="submit" value="Logout">
                          <span>Logout</span></button></p></form></li>';
                  }else{
                      echo '<li><form action="auth_register_form.php" method="GET">
                          <p><button type="submit" class="navbutton" name="authreg" value="login">
                          <span>Login</span></button></p></form></li>';
                      echo '<li><p>or<p></li>';
                      echo '<li><form action="auth_register_form.php" method="GET">
                          <p><button type="submit" class="navbutton" name="authreg" value="register">
                          <span>Register</span></button></p></form></li>';
                  }
                ?>
              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
</nav>