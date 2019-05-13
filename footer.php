<script>
  function onSignIn(googleUser) {
    // Useful data for your client-side scripts:
    var id_token = googleUser.getAuthResponse().id_token;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'https://www.sblwilliams.com/hollandale/login_backend.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      console.log(xhr.responseText);
      if (xhr.responseText == 'success') {
        window.location.replace('index.php');
      }
    };
    xhr.send('idtoken=' + id_token);
    /*
    console.log("ID: " + profile.getId()); // Don't send this directly to your server!
    console.log('Full Name: ' + profile.getName());
    console.log('Given Name: ' + profile.getGivenName());
    console.log('Family Name: ' + profile.getFamilyName());
    console.log("Image URL: " + profile.getImageUrl());
    console.log("Email: " + profile.getEmail());

    // The ID token you need to pass to your backend:
    var id_token = googleUser.getAuthResponse().id_token;
    console.log("ID Token: " + id_token);
    */
  };
</script>
<script>
  function signOut() {
    
    window.location.replace('logout.php');
    
  }
  
  function onLoad() {
    gapi.load('auth2', function() {
      gapi.auth2.init();
    });
  }
</script>

</body>
</html>