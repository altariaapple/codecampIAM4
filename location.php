<?php
  session_start();
  if(isset($_SESSION['userid'])){
    $user_id = $_SESSION['userid'];
  }
  else{
    unset($_SESSION['userid']);
    session_destroy();
  }
  require_once("system/data.php");


  // aktuelle URL bekommen, damit parameter am schluss geholt werden kann
  $current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  //die aktuelle URL wird in ihre einzelnen teile geparst; der teil 'query' gibt den teil aus welcher nach dem ? kommt
  $url_array = parse_url($current_url);

  //bild mit der ID die gleich dem url-parameter ist wird geholt
  $get_klicked_picture = get_certain_picture($url_array['query']);
  //die einträge aus der DB zum gewählten bild werden in einem array gespeichert
  $post_klicked_picture = mysqli_fetch_assoc($get_klicked_picture);

  // angaben zum bild-owner holen und in einem array speichern
  $get_klicked_picture_owner = get_picture_owner($post_klicked_picture['uploader']);
  $post_klicked_picture_owner = mysqli_fetch_assoc($get_klicked_picture_owner);


  //Like Button
  if(isset($_POST['like-submit'])){
    update_like($post_klicked_picture['picture_id'], $user_id);
  }

  //Freund hinzufüegen
  if(isset($_POST['freund_hinzufuegen'])){
    $result = freund_hinzufuegen($user_id, $post_klicked_picture['uploader']);
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
<p></p>

<body>

    <!-- Navigation wenn man eingeloggt ist-->
    <?php if(isset($_SESSION['userid'])){?>
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
                  <a class="navbar-brand" href="home.php">photoloc</a>
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
          </div>
          <!-- /.container -->
      </nav>
    <?php } ?>

    <!-- Navigation wenn man NICHT eingeloggt ist-->
  <?php if(!isset($_SESSION['userid'])){?>
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
    <?php } ?>

    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?php echo $post_klicked_picture['title']; ?>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Projects Row -->
        <div class="row">
            <div class="col-md-12 portfolio-item">
                <img class="img-responsive" src="../img_uploads/<?php echo $post_klicked_picture['img_src']; ?>" alt="">
                <div class="row">
                  <p class="col-lg-2"><?php echo $post_klicked_picture['timestamp']; ?></p>
                  <!-- Like Button nur möglich wenn man eingeloggt ist -->
                  <form enctype="multipart/form-data" action="<?php echo $current_url; ?>" method="post">
                    <?php if(isset($_SESSION['userid'])){ ?>
                      <button type="submit" name="like-submit" class="col-lg-1 col-lg-offset-3 likeButton"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span></button>
                      <!--  Likecounter -->
                      <p class="col-lg-1"><?php echo $post_klicked_picture['like_counter']; ?></p>
                    <?php } ?>
                  </form>
                </div>

                <p>geposten von <?php echo $post_klicked_picture_owner['first_name']. " " .$post_klicked_picture_owner['last_name']; ?></p>
                <p><?php echo $post_klicked_picture['description']; ?></p>
            </div>

            <form method="post">
              <button type="submit" class="btn btn-default" name="freund_hinzufuegen">Diesem User folgen</button>
            </form>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
