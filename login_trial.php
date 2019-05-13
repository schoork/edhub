<?php

session_start();

require_once 'google-api-php-client-2.0.2/vendor/autoload.php';
require_once 'connectvars.php';

// Get $id_token via HTTPS POST.
$id_token = $_POST['idtoken'];

$client = new Google_Client(['client_id' => $CLIENT_ID]);
$payload = $client->verifyIdToken($id_token);
if ($payload) {
  $userid = $payload['sub'];
  // If request specified a G Suite domain:
  $domain = $payload['hd'];
  if ($domain == 'hollandalesd.org') {
    $user_data = file_get_contents("https://classroom.googleapis.com/v1/userProfiles/{$userid}");
    $username = substr($payload['email'], 0, strlen($payload['email']) - 17);
    $query = "SELECT firstname, lastname, access, school, status, departments FROM staff_list WHERE username = '$username'";
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $result = mysqli_query($dbc, $query);
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_array($result);
      if ($row['status'] != 1) {
        //User is not active
        // remove all session variables
        session_unset();
        // destroy the session
        session_destroy();

      }
      else {
        print_r($payload);
      }
    }
    /*
    else {
      $firstname = $payload['given_name'];
      $lastname = $payload['family_name'];
      $query = "INSERT INTO staff_list (username, firstname, lastname, school, access, status) VALUES ('$username', '$firstname', '$lastname', 'Unknown', 'Teacher', 1)";
      if (mysqli_query($dbc, $query)) {
        $_SESSION['username'] = $username;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['access'] = 'Teacher';
        $_SESSION['school'] = 'Unknown';
        $_SESSION['status'] = 1;
        $_SESSION['domain'] = 'hollandale';
        $_SESSION['departments'] = '';
        echo 'success';
      }
    }
    */
  }

} else {
  // Invalid ID token
}

?>
