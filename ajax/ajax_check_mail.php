<?php
  require_once("../system/data.php");

  // mit $_GET wird der genannte Key geholt
  $result = checkmail_db($_GET["email"]);
  $row_count = mysqli_num_rows($result);
    if($row_count == 1){
      $userinfo = mysqli_fetch_assoc($result);
?>
      <div class="alert alert-success" role="alert">
        Klicke auf den Link, um das Passwort zurück zu setzen:
        <br>
        <a href="festlegen.php?user=<?php echo $userinfo['user_id'] ?>">www.photoloca.ch/passwordreset</a>
      </div>
<?php
    }
    else{
?>
      <div class="alert alert-danger" role="alert">
        Diese E-Mail ist nicht registriert.
      </div>
<?php
    }
?>
