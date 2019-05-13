<?php

$page_title = 'Login';
$page_access = 'All';
include('header.php');

?>
<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
  <div class="container">
    <h1>
      Welcome to edhub
    </h1>
    <p class="lead">
      Let us be at the center of what you do
    </p>
  </div>
</div>
<div class="container mt-5 mb-5">
  <div class="row">
    <div class="col-12">
      <h1>
        Login
      </h1>
      <p>
        In order to login you will need to sign in using your Hollandale Google account. This website will not know or save your password.
      </p>
      <p>
        <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
      </p>
    </div>
  </div>
</div>
</body>
  
<?php
  
include('footer.php');
  
?>