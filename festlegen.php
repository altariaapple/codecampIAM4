<?php
  session_start();
  if(isset($_SESSION['id'])) unset($_SESSION['id']);
  session_destroy();

  require_once("system/data.php");

  $error = false;
  $error_msg = "";
  $success = false;
  $success_msg = "";

  if(isset($_POST['login-submit'])){
    if(!empty($_POST['username']) && !empty($_POST['password'])){

      $username = filter_inputs($_POST['username']);
      $password = filter_inputs($_POST['password']);

      $result = login($username, $password);

  		$row_count = mysqli_num_rows($result);
      if( $row_count == 1){
        session_start();
        $user = mysqli_fetch_assoc($result);
        $_SESSION['userid'] = $user['user_id'];
        header("Location:home.php");
      }
      else{
        $error = true;
        $error_msg .= "Benutzerdaten konnten nicht gefunden werden.</br>";
      }
    }
    else{
      $error = true;
      $error_msg .= "Bitte füllen Sie beide Felder aus.</br>";
    }
  }


  if(isset($_POST['register-submit'])){
  // Kontrolle mit isset, ob email und password ausgefüllt wurde
  if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['confirm-password'])){

    // Werte aus POST-Array auf SQL-Injections prüfen und in Variablen schreiben
    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    if($password == $confirm_password){
      // register liefert bei erfolgreichem Eintrag in die DB den Wert TRUE zurück, andernfalls FALSE
      $result = register($username, $email, $firstname, $lastname, $password);
      if($result){
        $success = true;
        $success_msg = "Ihre Registrierung war erfolgreich.</br>
        Sie können sich nun einloggen.</br>";
      }else{
        $error = true;
        $error_msg .= "Es gibt ein Problem mit der Datenbankverbindung.</br>";
      }
    }else{
      $error = true;
      $error_msg .= "Die Passwörter stimmen nicht überein.</br>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Photoloca</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/3-col-portfolio.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

  <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Photoloca</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="index.php">Finde Locations</a>
                    </li>
                    <li>
                        <a href="login.php">Login / Registration</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
      <!-- /.container -->
    </nav>

  <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-6">
              <!--  Neues Passwort festlegen form -->
              <h3>Neues Passwort festlegen</h3>
              <form id="login-form" action="<?PHP echo $_SERVER['PHP_SELF'] ?>" method="post" role="form">
                <div class="form-group">
                  <h5>Neues Passwort</h5>
                  <input type="password" name="password" id="password" tabindex="1" class="form-control" placeholder="Neues Passwort" value="">
                </div>
                <div class="form-group">
                  <h5>Passwort bestätigen</h5>
                  <input type="password" name="password2" id="password2" tabindex="2" class="form-control" placeholder="Passwort bestätigen">
                </div>
                <div class="form-group">
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3">
                    <input type="submit" name="password-submit" id="password-submit" tabindex="3" class="form-control btn btn-register" value="Neues Passwort speichern">
                  </div>
                </div>
              </div>
              </form>
              <!-- /Neues Passwort festlegen  -->

              <!-- Fehlermeldung: Die PW stimmen nicht überein -->

              <!-- /Fehlermeldung: Die PW stimmen nicht überein -->

            </div>
          </div>
          <!-- /.row -->
            <?php
              // Gibt es einen Erfolg zu vermelden?
              if($success == true){
            ?>
                <div class="alert alert-success" role="alert"><?php echo $success_msg; ?></div>
            <?php
              }   // schliessen von if($success == true)
              // Gibt es einen Fehler?
              if($error == true){
            ?>
                <div class="alert alert-danger" role="alert"><?php echo $error_msg; ?></div>
            <?php
              }   // schliessen von if($success == true)
            ?>

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Photoloca 2017</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- functions.js -->
    <script src="js/functions.js"></script>

</body>

</html>