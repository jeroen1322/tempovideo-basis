<?php
include(__DIR__ . '/../db.php');
session_start();
// session_unset($_SESSION['login']);
// if($_SESSION['login'] == $klandId){
//   echo "test";
// }else{
//   echo "nope";
// }
$klantId = $_SESSION['login'];
echo $klantId;
echo $naam;
print_r($_SESSION);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Material Design fonts -->
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">
    <title><?= $this->escape($this->pageTitle); ?></title>

    <!-- Bootstrap -->
    <link href="../bootstrap/css/bootstrap.min.css " rel="stylesheet">

    <!-- Bootstrap Material Design -->
    <link rel="stylesheet" type="text/css" href="../bootstrap-material/css/bootstrap-material-design.css">
    <link rel="stylesheet" type="text/css" href="../bootstrap-material/css/ripples.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <!-- <link rel="stylesheet" type="text/css" href="dist/css/ripples.min.css"> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="navbar navbar-default">
      <div class="container-fluid container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">TEMPOVIDEO</a>
        </div>
        <div class="navbar-collapse collapse navbar-responsive-collapse">
          <ul class="nav navbar-nav">
            <li><a href="/">HOME</a></li>
            <li><a href="/film/aanbod">FILMAANBOD</a></li>
            <li><a href="/over_ons">OVER ONS</a></li>
            <li><a href="/contact">CONTACT</a><li>
          </ul>
            <ul class="nav navbar-nav menu_right">
              <li><a href="/login">LOGIN</a></li>
              <li><a href="/registreer">REGISTREER</a></li>
            </ul>
            <ul class="nav navbar-nav menu_right">
              <li><a href="#">INGELOGD</a></li>
            </ul>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="content">
            <?= $this->yieldView(); ?>
      </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
     <script src="/bootstrap/js/bootstrap.min.js"></script>
     <script src="/bootstrap-material/js/material.js"></script>
     <script src="/bootstrap-material/js/ripples.js"></script>
  </body>
</html>