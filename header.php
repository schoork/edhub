<?php

//require_once 'google-api-php-client-2.0.2/vendor/autoload.php';

session_start();


if ($_SESSION['domain'] == 'hollandale') {
  if ($_SERVER['PHP_SELF'] == '/hollandale/login.php') {
		header("Location: https://www.sblwilliams.com/hollandale/index.php");
	}
	else {
		//Checks access permissions
		if ($page_access != 'All' && strpos($page_access, $_SESSION['access']) === false) {
			header("Location: https://www.sblwilliams.com/hollandale/restricted.php");
		}
	}
}
else {
	if ($_SERVER['PHP_SELF'] != '/hollandale/login.php') {
		// remove all session variables
		session_unset();
		// destroy the session
		session_destroy();
		header("Location: https://www.sblwilliams.com/hollandale/login.php");
	}
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
		 <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>
		<meta name="google-signin-client_id" content="459930072424-9u4i7lrrju8lfa6nbgokd6tkeol92ru8.apps.googleusercontent.com">

		<!--Bootstrap-->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<link href="css/header_styles.css" rel="stylesheet">

	<!--Import print CSS-->

	<!--Fav Icon-->
	<link rel="shortcut icon" href="images/logo2.png" type="image/x-icon">

<?php if ($_SESSION['access'] == 'Admin') {
  ?>
  <script>
  console.log('This page is accessible to <?php echo $page_access; ?>');
  </script>
<?php }

  echo '<title>' . $page_title . ' </title>';
?>
