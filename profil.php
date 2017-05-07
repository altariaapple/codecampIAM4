<?php
  session_start();
  if(!isset($_SESSION['userid'])){
    header("Location:index.php");
  }
  else{
    $user_id = $_SESSION['userid'];
  }

  require_once('system/data.php');

  $result = get_user($user_id);
  $user = mysqli_fetch_assoc($result);

  $post_favorite_pictures = get_favorite_pictures($user_id);
  $post_my_pictures = get_my_pictures($user_id);

  if(isset($_POST['update-submit']))
  {
    // $profilfoto = filter_inputs($_POST[' nprofil_img']);
    $firstname = filter_inputs($_POST['firstname']);
    $lastname = filter_inputs($_POST['lastname']);
    $email = filter_inputs($_POST['email']);
    $password = filter_inputs($_POST['password']);
    $confirm_password = filter_inputs($_POST['confirm-password']);

    $result = update_user($firstname, $lastname, $email, $password, $confirm_password, $user_id);
  }

  //Freund hinzufüegen
  if(isset($_POST['del_friends'])){
    $remove_friend = filter_inputs($_POST['del_friends']);
    $result = remove_friends($user_id, $remove_friend);
}

$friend_list = get_friend_list($user_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Profil</title>

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
                <a class="navbar-brand" href="#">Photoloc</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="home.php">Finde Locations</a>
                    </li>
                    <li>
                        <a href="profil.php">Mein Profil</a>
                    </li>
                    <li>
                        <a href="index.php">Logout</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
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
            <div class="col-lg-12">
                <h1 class="page-header">Dein Profil <br>
                    <small>Hier kannst du alles ändern </small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Sub Nav -->
           <div class="row">
             <div class="col-md-12 col">
               <!-- boostrap component Button group: drei Buttons nebeineinander -->
               <div class="btn-group btn-group-justified" role="group" aria-label="...">
                 <div class="btn-group" role="group">
                   <button type="button" class="btn btn-default" id="profilfoto" value="profilfoto">Einstellungen</button>
                 </div>
                 <div class="btn-group" role="group">
                   <button type="button" class="btn btn-default" id="meinefotos" value="meinefotos">Meine Fotos</button>
                 </div>
                 <div class="btn-group" role="group">
                   <button type="button" class="btn btn-default" id="favoriten" value="favoriten">Favoriten</button>
                 </div>
                 <div class="btn-group" role="group">
                   <button type="button" class="btn btn-default" id="follower" value="follower">Follower</button>
                 </div>
               </div>
               <!-- Platzhalter für Inhalte, wird durch JS mit Inhalt gefüllt. Je nach geklicktem Button -->
            <div class="col-md-12 col">
              <p class="layoutonclick"></p>
            </div>
       <!-- Ende Subnav -->

<!--Section Einstellungen-->
 <section id="profilfoto">
        <!-- Einstellungen -->
        <h2>Passe hier deine Profileinstellungen an</h2>
        <div class="row">
                   <div class="col-md-12 col-lg-12 portfolio-item">
                     <div class="col-md-8 col-sm-8">
                <!--Start Formular -->
                <form enctype="multipart/form-data" action="profil.php" method="post">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Persönliche Einstellungen</h4>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            </div>
          </div>
          <div class="form-group row">
            <label for="Vorname" class="col-sm-2 col-xs-12 form-control-label">Name</label>
            <div class="col-sm-5 col-xs-6">
              <input  type="text" class="form-control form-control-sm"
                      id="Vorname" placeholder=""
                      name="firstname" value="<?php echo $user['first_name'];?>">
            </div>
            <div class="col-sm-5 col-xs-6">
              <input  type="text" class="form-control form-control-sm"
                      id="Nachname" placeholder=""
                      name="lastname" value="<?php echo $user['last_name'];?>">

            </div>
          </div>
          <div class="form-group row">
            <label for="Email" class="col-sm-2 form-control-label">E-Mail</label>
            <div class="col-sm-10">
              <input  type="email" class="form-control form-control-sm"
                      id="Email" placeholder="" required
                      name="email" value="<?php echo $user['email'];?>">
            </div>
          </div>
          <div class="form-group row">
            <label for="Passwort" class="col-sm-2 form-control-label">Password</label>
            <div class="col-sm-10">
              <input type="password" class="form-control form-control-sm" id="Passwort" placeholder="Passwort" name="password">
            </div>
          </div>
          <div class="form-group row">
            <label for="Passwort_Conf" class="col-sm-2 form-control-label">Passwort bestätigen</label>
            <div class="col-sm-10">
              <input type="password" class="form-control form-control-sm" id="Passwort_Conf" placeholder="Passwort" name="confirm-password">
            </div>
          </div>

          <div class="form-group row">
            <!-- http://plugins.krajee.com/file-input -->
            <label for="Tel" class="col-sm-2 form-control-label"></label>
            <div class="col-sm-10">

            </div>
          </div>
        </div> <br>
        <div class="col-md-12">
          <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Abbrechen</button>
            <button type="submit" class="btn btn-success btn-sm" name="update-submit">Änderungen speichern</button>
        </div>
        </div>
      </form>

                <!--Ende Formular -->
 </section>

 <section id="meinefotos">
<!--Start Section Meine Fotos-->

  <h2>Deine Fotos</h2>
      <!-- Fotos -->
      <?php while($pictures = mysqli_fetch_assoc($post_my_pictures)) {
          $post_picture_owner = mysqli_fetch_assoc(get_picture_owner($pictures['uploader']));

          //die $pictureID wird als Parameter dem Bild-Link übergeben
          $pictureID = $pictures['picture_id'];
        ?>
            <div class="col-md-4">
                <a href="location.php?<?php echo $pictureID; ?>">
                    <img class="img-responsive" src="img_uploads/<?php echo $pictures['img_src']; ?>" alt="">
                </a>
                <h3>
                    <a href="location.php?<?php echo $pictureID; ?>"><?php echo $pictures['title']; ?></a>
                </h3>
                <p><?php echo $pictures['description']; ?></p>
                <p>Tags</p>
                <p>von <?php echo $post_picture_owner['first_name']. " " .$post_picture_owner['last_name']; ?></p>
            </div>

      <?php }?>
      </div>
 </section>
 <!--Ende Section Meine Fotos-->

 <section id="favoriten">
   <!--Start Section Favoriten-->
    <h2>Fotos die du geliked hast</h2>
    <!-- Fotos -->
    <!-- Locations Row -->
    <div class="row">
    <?php while($pictures = mysqli_fetch_assoc($post_favorite_pictures)) {
        $post_picture_owner = mysqli_fetch_assoc(get_picture_owner($pictures['uploader']));

        //die $pictureID wird als Parameter dem Bild-Link übergeben
        $pictureID = $pictures['picture_id'];
      ?>
          <div class="col-md-4">
              <a href="location.php?<?php echo $pictureID; ?>">
                  <img class="img-responsive" src="img_uploads/<?php echo $pictures['img_src']; ?>" alt="">
              </a>
              <h3>
                  <a href="location.php?<?php echo $pictureID; ?>"><?php echo $pictures['title']; ?></a>
              </h3>
              <p><?php echo $pictures['description']; ?></p>
              <p>Tags</p>
              <p>von <?php echo $post_picture_owner['first_name']. " " .$post_picture_owner['last_name']; ?></p>
          </div>

    <?php }?>
    </div>
    <!-- /.row -->
 </section>
 <!--Ende Section Favoriten-->


 <section id="follower">
  <!-- Meine Freunde -->
  <!-- Seitenleiste -->
        <div class="col-md-12">
          <!-- Userliste -->
          <div class="panel panel-default">
            <div class="panel-heading">Meine Freunde
            <div class="panel-body">
              <form method="post" action="<?PHP echo $_SERVER['PHP_SELF'] ?>" >
                <?php while($user = mysqli_fetch_assoc($friend_list)) {?>
              <!-- User als Freund hinzufügen -->
                <div class="form-group row p42-form-group">
                  <input type="submit" name="del_friends" id="userid<?php echo $user['user_id'] ?>" autocomplete="off" value="<?php echo $user['user_id']; ?>" />
                  <div class="btn-group col-xs-12">
                    <label for="userid<?php echo $user['user_id'] ?>" class="btn btn-default col-xs-2 col-sm-1 col-md-2">
                      <span class="glyphicon glyphicon-minus"></span>
                    </label>
                    <label for="userid<?php echo $user['user_id'] ?>" class="btn btn-default active col-xs-10 col-sm-11 col-md-10">
                        <?php echo $user['first_name'] . " " . $user['last_name']; ?>
                    </label>
                    </div>
                  </div>
                  <?php
                 }
                 ?>
               </form>
              </div>
            </div>
          </div>
        </div>
  </section>


        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Your Website 2014</p>
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

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
       <!-- Include all compiled plugins (below), or include individual files as needed -->
       <script src="js/bootstrap.min.js"></script>
       <script src="plugins/datepicker/js/bootstrap-datepicker.js"></script>
       <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
       <script src="js/functions.js"></script>


</body>

</html>
