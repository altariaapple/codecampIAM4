<?php
  require_once("../system/data.php");

  // mit $_GET wird der genannte Key geholt
  $result = checkmail_db($_GET["email"]);
  $row_count = mysqli_num_rows($result);
    if($row_count == 1){
?>
      <div class="alert alert-success" role="alert">
        Klicke auf den Link, um das Passwort zurück zu setzen:
        <br>
        <a href="festlegen.php?code=CidnenjaOIfbfq83">www.photoloca.ch/passwordrese?code=CidnenjaOIfbfq83</a>
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
