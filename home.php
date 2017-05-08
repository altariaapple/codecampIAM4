<?php
  session_start();
  if(!isset($_SESSION['userid'])){
    header("Location:index.php");
  }
  else{
    $user_id = $_SESSION['userid'];
  }
  require_once("system/data.php");

  $post_pictures = get_all_pictures();

  $get_tags = get_tags();

  require_once('system/data.php');

  $result = get_user($user_id);
  $user = mysqli_fetch_assoc($result);
    $upload_ok = true;
//Bildupload
  if(isset($_POST['update-submit']))
{
  $description = filter_inputs($_POST['description']);
  $title = filter_inputs($_POST['title']);
  $img_src = "";
  $uploader = $user_id;
  $image_name="";
  $like_counter=0;

  $upload_path = "img_uploads/";
  $max_file_size = 1000000;
  $upload_ok = true;


//Schranke Dateiname
if ($_FILES['post_img']['name'] != "") {
  $filetype = $_FILES['post_img']['type'];
  switch ($filetype) {
    case "image/jpg":
             $file_extension = "jpg";
             break;
         case "image/jpeg":
             $file_extension = "jpeg";
             break;
         case "image/gif":
             $file_extension = "gif";
             break;
         case "image/png":
             $file_extension = "png";
             break;
         default: //falls nichts zutrifft:
           $upload_ok = false;
     }
     $upload_filesize = $_FILES["post_img"]["size"];
         if ( $upload_filesize >= $max_file_size) {
             echo "Ach mann, immer diese Schranken! Ihr Bild ist mit". $upload_filesize." KB zu gross. <br> Sie darf nicht grösser als". $max_file_size." sein." ;
             $upload_ok = false;
         }

        if($upload_ok){
          $image_name = time() . "_" . $user['user_id'] . "." . $file_extension;
          move_uploaded_file($_FILES['post_img']['tmp_name'], $upload_path . $image_name );
          echo '<script type="text/javascript">alert("Es hat alles geklappt.");</script>';
          $result = bildupload($uploader, $like_counter, $description, $title, $image_name);
        }else{
          echo "Sorry!!!! aber irgendetwas hat nicht funktioniert. Versuchen Sie es doch einfach noch einmal";
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

    <title>Photolocaaa</title>

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

    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Hallo <?php echo $user['benutzer_name'];?>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Bild hochladen -->
        <div class="panel panel-default container-fluid"> <!-- fluid -->
          <div class="panel-heading row">
            <div class="col-sm-6">
                <h4>Laden Sie hier ein Bild hoch!</h4>
            </div>
            <div class="col-xs-6 text-right">
                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#myModal">Bild hochladen</button>
            </div>
          </div>
          <div class="panel-body">
            <div class="col-sm-3">
              <!-- Bild -->
            </div>
          </div>
        </div>

        <!-- Shadowbox Bild hochladen -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <form enctype="multipart/form-data" action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="modal-header">
                  <h4 class="modal-title" id="myModalLabel">Laden Sie hier ein Bild hoch</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group row">
                    <label for="Gender" class="col-sm-2 form-control-label">Foto</label>
                    <div class="form-group row">
                      <!-- http://plugins.krajee.com/file-input -->
                      <label for="Tel" class="col-sm-2 form-control-label"></label>
                      <div class="col-sm-10">
                        <input type="file" name="post_img">
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="Vorname" class="col-sm-2 col-xs-12 form-control-label">Titel</label>
                    <div class="col-sm-6 col-xs-6">
                      <input type="text" name="title" required>
                    </div>
                  <div class="form-group row">
                    <br><br>
                    <label for="Vorname" class="col-sm-2 col-xs-12 form-control-label">Beschreibung</label>
                    <div class="col-sm-6 col-xs-6">
                      <textarea rows="4" cols="50" name="description" required>
                      </textarea>

                    </div>
                    <div class="col-sm-5 col-xs-6">

                    </div>
                  </div>
                  <!-- <div class="form-group row">
                    <label for="Email" class="col-sm-2 form-control-label">Location</label>
                    <div class="col-sm-10">
                        <input type="checkbox" name="location" value="bern"> bern <br>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="Passwort" class="col-sm-2 form-control-label">Tags</label>
                    <div class="col-sm-10">
                        <input type="checkbox" name="tags" value="sonnig"> sonnig <br>
                      </div>
                  </div> -->

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Abbrechen</button>
                  <button type="submit" class="btn btn-success btn-sm" name="update-submit">Bild hochladen</button>

                </div>
              </form>

            </div>
          </div>
        </div>
      </div>

        <!-- Locations Row -->
        <div class="row">
        <?php while($pictures = mysqli_fetch_assoc($post_pictures)) {
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

        <!-- Pagination -->
        <!-- <div class="row text-center">
            <div class="col-lg-12">
                <ul class="pagination">
                    <li>
                        <a href="#">&laquo;</a>
                    </li>
                    <li class="active">
                        <a href="#">1</a>
                    </li>
                    <li>
                        <a href="#">2</a>
                    </li>
                    <li>
                        <a href="#">3</a>
                    </li>
                    <li>
                        <a href="#">4</a>
                    </li>
                    <li>
                        <a href="#">5</a>
                    </li>
                    <li>
                        <a href="#">&raquo;</a>
                    </li>
                </ul>
            </div>
        </div> -->
        <!-- /.row -->

        <!-- <hr> -->

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Photoloc 2016</p>
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
