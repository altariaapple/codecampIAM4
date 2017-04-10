<?php

  /* ---- general stuff ---- */
  /* ----------------------- */

  /* verbindung mit db */
  function get_db_connection()
  {
    $db = mysqli_connect('localhost', '297957_6_1', 'lMtfWvh789yb', '297957_6_1')
      or die('Fehler beim Verbinden mit dem Datenbank-Server.');
      mysqli_query($db, "SET NAMES 'utf8'");
    return $db;
  }

  /* funktion zu sql abfragen */
  function get_result($sql)
  {
    $db = get_db_connection();
    //echo $sql ."<br>";
    $result = mysqli_query($db, $sql);
    mysqli_close($db);
    return $result;
  }
 /* ----------------------------------------------------------------------------- */


 /* ---- security stuff ---- */
 /* ----------------------- */

  function filter_inputs($input){
    $db = get_db_connection();

    // HTML- und PHP-Codes wegfiltern: strip_tags(variable)
    $input = strip_tags($input);
    // Leerzeichen am Anfang und Ende der Zeichenkette entfernen
    $input = trim($input);
    // SQL-Injection (einschmggeln von SQL-Befehlen) verhindern
    $input = mysqli_real_escape_string($db, $input);
    mysqli_close($db);
    return $input;
  }
 /* ----------------------------------------------------------------------------- */


 /* ---- index.php ---- */
 /* ----------------------- */

 function get_all_pictures(){
   $sql = "SELECT * FROM picture ORDER BY timestamp DESC" ;
   return get_result($sql);
 }

 function get_picture_owner($ownerID){
   $sql = "SELECT first_name, last_name FROM user WHERE user_id = '$ownerID';";
   return get_result($sql);
 }
 /* ----------------------------------------------------------------------------- */


 /* ---- login.php ---- */
 /* ----------------------- */

 function register($username, $email, $firstname, $lastname, $password){
   $sql = "INSERT INTO user (benutzer_name, email, first_name, last_name, password) VALUES ('$username', '$email', '$firstname', '$lastname', '$password');";
   return get_result($sql);
 }

 function login($username, $password){
   $sql = "SELECT * FROM user WHERE benutzer_name = '$username' AND password = '$password';";
   return get_result($sql);
 }
 /* ----------------------------------------------------------------------------- */


  /* ---- location.php ---- */
  /* ----------------------- */
  function get_certain_picture($picID){
    $sql = "SELECT * FROM picture WHERE picture_id = '$picID';";
    return get_result($sql);
  }

  function update_like($picID, $userID){
    $check_sql = "SELECT * FROM likes2 WHERE liker = '$userID' AND picture = '$picID';";
    $check_result = get_result($check_sql);
    $row_count_check = mysqli_num_rows($check_result);
    $like_ok = false;

    if($row_count_check == 0){
      count_like($picID);
      $sql = "INSERT INTO likes2 (liker, picture) VALUES ('$userID', '$picID');";
      header("Refresh:0");    // seite refreshen damit like gerade im counter angezeigt wird
      return get_result($sql);
    }
    else{
      $meldung = "Du hast dieses Bild bereits geliked";
      echo $meldung;
    }
  }

  function count_like($picID){
    // like counter erhöhen
    $sql = "UPDATE picture SET like_counter = like_counter+1 WHERE picture_id = '$picID';";
    return get_result($sql);
  }
  /* ----------------------------------------------------------------------------- */

  /* ---- allgemein ---- */
  /* ----------------------- */
  function get_tags(){
    $sql = "SELECT * FROM tags;";
    return get_result($sql);
  }

  /* ----------------------------------------------------------------------------- */


  /* ---- home.php ---- */
  /* ----------------------- */

  //Bildupload
  function bildupload($uploader, $like_counter, $description, $title, $img_src){
    $sql = "INSERT INTO picture (uploader, like_counter, description, title, img_src) VALUES ('$uploader', '$like_counter', '$description', '$title', '$img_src');";
   // echo '<script type="text/javascript">alert("' . $sql . '");</script>';
     header("Refresh:0");
     return get_result($sql);
  }


  function get_user($user_id){
      $sql = "SELECT * FROM user WHERE user_id = $user_id;";
      return get_result($sql);
  }

  /* ---- profil.php ---- */
  /* ----------------------- */
  // userdaten updaten
  function update_user($firstname, $lastname, $email, $password, $confirm_password, $user_id){
      $sql_ok = false;
      $sql = "UPDATE user SET ";
    //   if($profilfoto != ""){ //wenn profilfoto nicht leer dann:
    //     $sql .= "profilfoto = '$profilfoto', ";  //vorhandener string wird erweitert
    //     $sql_ok = true;
    // }

      if($firstname != ""){
        $sql .= "first_name = '$firstname', ";
        $sql_ok = true;
      }

      if($lastname != ""){
        $sql .= "last_name = '$lastname', ";
        $sql_ok = true;
      }

      if($email != ""){
        $sql .= "email = '$email', ";
      }

      if($password != "" && $confirm_password == $password){
        $sql .= "password = '$password', ";
        $sql_ok = true;
      }

      $sql = substr_replace($sql, ' ', -2, 1);
      $sql .= "WHERE user_id = $user_id;";

      if($sql_ok)
      {
        header("Refresh:0");
        return get_result($sql);

      } else{
        return false;
      }

}



    //meine bilder
    function get_my_pictures($userID){
      $sql = "SELECT * from picture WHERE uploader = '$userID';";
      return get_result($sql);
    }

    //favoriten
    function get_favorite_pictures($userID){
      $sql = "SELECT * FROM picture p WHERE p.picture_id IN
              (SELECT picture FROM likes2 WHERE liker = '$userID');";
      return get_result($sql);
    }

    //followers
    function get_friend_list($user_id){
          $sql = "SELECT * FROM user WHERE user_id in
                    (SELECT user_id_followed FROM follower2 WHERE user_id_follower = $user_id)
                    AND  NOT user_id = $user_id;";
      		return get_result($sql);
  	}


    function freund_hinzufuegen($user_id, $get_klicked_picture_owner){
      $sql = "INSERT INTO follower2 (`user_id_follower`, `user_id_followed`) VALUES ($user_id, $get_klicked_picture_owner);";
      return get_result($sql);
   }

   function remove_friends($user_id, $remove_friend){
      $sql = "DELETE FROM follower2 WHERE user_id_follower = $user_id AND user_id_followed = $remove_friend;";
      return get_result($sql);
    }


  ?>
