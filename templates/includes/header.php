<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Starter Template · Bootstrap</title>

    <!-- Bootstrap core CSS -->
 	<link href="<?php echo BASE_URI;?>templates/css/bootstrap.css" rel="stylesheet">
 	<link href="<?php echo BASE_URI;?>templates/css/custom.css" rel="stylesheet">

    <!-- Favicons -->
<meta name="theme-color" content="#563d7c">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <?php if(!isset($title)){
      $title = SITE_TITLE;
    }?>
  </head>
      <body>
      <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="index.php">Talkingspace</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>


      <div class="collapse navbar-collapse" style="justify-content: flex-end;" id="navbarsExampleDefault">
        <ul class="navbar-nav" style="margin-right: 100px">
          <li class="nav-item active">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <?php if(!isLoggedIn()):?>
          <li class="nav-item">
            <a class="nav-link" href="register.php">Create An Account</a>
          </li>
        <?php else :?>
          <li class="nav-item">
            <a class="nav-link" href="create.php">Create Topic</a>
          </li>
        <?php endif;?>
        </ul>
      </div>

    </nav>

    <main role="main" class="container">
      <div class="row">
        <div class="col-md-8">
          <div class="main-col">
            <div class="block">
              <h2 class="float-left"><?php echo $title;?></h2>
              <h5 class="float-right">A simple PHP form engine</h5>
              <div class="clearfix"></div>
              <hr>
              <?php displayMessage();?>