<?php

  session_start();
  if(isset($_SESSION['userid'])) unset($_SESSION['userid']);
  session_destroy();

  require_once('system/data.php');


  $error = false;
  $error_msg = "";
  $success = false;
  $success_msg = "";

  $post_pictures = get_all_pictures();

  $get_tags = get_tags();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>AJAX Code Camp</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Custom CSS -->
    <link href="css/3-col-portfolio.css" rel="stylesheet">

    <!-- Bootstrap Toggle -->
    <link href="css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="js/bootstrap-toggle.min.js"></script>

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
                        <a href="#" id="suchfeldLink">Finde Locations</a>
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

    <!--  Suchfeld Content -->
    <div class="container suchfeld" style="display: none;">
      <div class="row">
        <h4>Suche nach diesen Tags</h4>
        <?php
          $tagcounter = 0;
          while($post_tags = mysqli_fetch_assoc($get_tags)){?>
          <div class="col-lg-2 tagToggle">
            <input type="checkbox" unchecked data-toggle="toggle" data-on="<?php echo $post_tags['tag_name'];?>" data-off="<?php echo $post_tags['tag_name'];?>" data-onstyle="success">
          </div>
          <?php
          $tagcounter ++;
          if ($tagcounter == 6){ ?>
          </div>
          <div class="row">
          <?php
              } //if beenden
            } //while beenden
          ?>
        </div>
    </div>
    <!-- /suchfeld -->

    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Photoloca
                    <small>Machen Sie sich ein Bild davon!  </small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

      <div class="row">
      <?php while($pictures = mysqli_fetch_array($post_pictures)) {
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

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Photoloca 2016</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- functions.js -->
    <script src="js/functions.js"></script>

</body>

</html>
