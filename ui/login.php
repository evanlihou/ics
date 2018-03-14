<?php
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}
?>
<html>
  <head>
  <!-- Standard Meta -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Log In :: Inventory Check* System</title>
    <script
      src="https://code.jquery.com/jquery-3.1.1.min.js"
      integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
      crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="/assets/semantic.min.css">
    <script
      src="https://code.jquery.com/jquery-3.1.1.min.js"
      integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
      crossorigin="anonymous"></script>
    <script src="/assets/semantic.min.js"></script>
    <script src="/assets/tablesort.js"></script>
    <script src="/assets/alertify.js"></script>
    <!--<link rel="stylesheet" href="/assets/themes/semantic.css">-->
    <link rel="stylesheet" href="/assets/css/alertify.css">

  <style type="text/css">
    body {
      background-color: #DADADA;
    }
    body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
    .column {
      max-width: 450px;
    }
  </style>
  <script>
  $(document)
    .ready(function() {
      $('.ui.form')
        .form({
          fields: {
            email: {
              identifier  : 'user',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your username'
                },
              ]
            },
            password: {
              identifier  : 'password',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your password'
                }
              ]
            }
          }
        })
      ;
    })
  ;
  </script>
</head>
<body style="">
<div class="ui middle aligned center aligned grid">
  <div class="column">
    <?php
    if ($f3->get('PARAMS')) {
        $msg = array(
          'user' => 'You have successfully logged out.',
          'expired' => 'Your session has expired.',
          'invalid' => 'Your username or password is invalid.',
          'C02' => 'Your account does not have access to this page. Log in as an account that does.',
          'C03' => 'You are not logged in.'
        );
        if ($f3->get('PARAMS.reason') !== '' && $f3->get('PARAMS.reason')) {
            echo '<div class="ui error message"><p>'.$msg[$f3->get('PARAMS.reason')].'</div>';
        }
    }
    ?>
    <div class="ui icon error message">
      <i class="error icon"></i>
      <div class="content">
        <div class="header">
          Heads up!
        </div>
        <p>We're currently down for some server maintenance.</p>
        <p>Check out <a href="http://changes.evanlihou.com/server-maintenance-25440">this post</a> for more info!</p>
      </div>
    </div>
    <h2 class="ui black image header">
      <img class="image" src="/assets/images/logoTransparent.svg">
      <div class="content">
        Login
      </div>
    </h2>
    <form class="ui large form" method="post" action="/authenticate">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="user" placeholder="Username" autofocus autocorrect="off" autocapitalize="off" spellcheck="false">
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="Password">
          </div>
        </div>
        <input type="text" name="redirect" value="<?= $f3->get('GET.r') ?>" hidden>
        <div class="ui fluid large black submit button">Login</div>
      </div>

      <div class="ui error message"></div>

    </form>

    <div class="ui message">
      Don't have an account? Ask an admin to create one for you.
    </div>
  </div>
</div>




</body></html>
