<?php
  require_once("../system/data.php");

  // mit $_GET wird der genannte Key geholt
  $result = update_new_pw($_GET["password"],$_GET['user_id']);

  if($result){
?>
      <div class="alert alert-success" role="alert">
        Dein neues Passwort wurde erfolgreich gespeichert.
      </div>
      <script>
        $('#login-form').hide();
      </script>
<?php
    }
    else{
?>
      <div class="alert alert-danger" role="alert">
        Es ist ein Fehler aufgetreten.
      </div>
<?php
    }
?>
