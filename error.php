<?php
function errorMsg($code,$status,$text,$trace) {
    global $f3;
    // Custom Errors
    $customError = array(
      'C99' => array('status' => 'Page is not complete', 'text' => 'Check back later for more information. If you need to submit a request to shoot, please do it on paper.'),
      'C02' => array('status' => 'Not authorized', 'text' => 'You are not logged in or do not have access to this page. Please <a href="/login>log in</a> or contact an administrator.')
    );
    if (isset($customError[$code])) {
        $status = $customError[$code]['status'];
        $text = $customError[$code]['text'];
    }
?>
  <html>
  <head>
    <meta charset="UTF-8">
    <title>Error :: Inventory Check* System</title>
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
    <script src="/assets/alertify.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/css/alertify.css">
  </head>
  <body>
  <div class="ui inverted stackable red menu">
    <div class="ui container">
      <a href="/" class="header item"><i class="large sign out icon"></i>ICS</a>
    </div>
    <div class="right menu">
    <?= ($f3->get('SESSION')? '<a class="ui item" href="/logout/user">Logout</a>' : '<a class="ui item" href="/login/c02">Login</a>') ?>
  </div>
  </div>
  <div class="ui main text container" style="margin-top:2rem;">
    <div class="ui middle aligned">
      <div class="ui very padded segment">
<?php
  // Start error printout
  print '<h1>Error '.$code.'</h1>';
  print '<h3>'.$status.'</h3>';
  print '<p>'.$text.'</p>';
  print '<div class="ui styled accordion"><div class="title"><i class="dropdown icon"></i>Reveal trace</div><div class="content">'.
    '<pre>'.$trace.'</pre></div></div>';

  print <<<HTML
  <div class="ui error message">
  For assistance with this issue, send an email with all of the above information to <a href="mailto:help@evanlihou.com">help@evanlihou.com</a>
  </div>
  <script>$('.ui.accordion').accordion(); $('div.page').transition('fade up'); </script>
HTML;
}
?>
</body>
</html>
