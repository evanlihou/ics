<?php
function makeHeader(string $title, string $color){
  global $f3;
  print <<<HTML
  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>$title :: Inventory Check* System</title>
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
    <link rel="stylesheet" href="/assets/css/alertify.css">
  </head>
  <body>
  <div class="ui inverted stackable $color menu">
    <div class="ui container">
      <a href="#" class="header item logo"><i class="large sign out icon"></i>ICS</a>
HTML;
  function menuItem($name, $link, $g = '') {
    global $f3;
    if ($f3->get('SESSION.groupId') >= $g) {
    print '<a href="'.$link.'" class="'.(''.$f3->get('PATH') == $link?'active':'').' item">'.$name.'</a>';
  }
  }
  menuItem('Home','/',1);
  if ($f3->get('SESSION.groupId') >= 2) {
  // Item
  print '<div class="ui dropdown item">Item
    <i class="dropdown icon"></i>
    <div class="menu">';
      menuItem('List Items - 121','/item/list/121',2);
      menuItem('List Items - 100','/item/list/100',2);
      menuItem('Check In','/item/checkin',2);
      menuItem('Check Out','/item/checkout',2);
      menuItem('New Item - 121','/item/new/121',3);
      menuItem('New Item - 100','/item/new/100',3);
  print '  </div>
  </div>'; }
  // Request
  if ($f3->get('SESSION.groupId') >= 1) {
    print '<div class="ui dropdown item">Request
    <i class="dropdown icon"></i>
    <div class="menu">';
      menuItem('New Request','/request/new',1);
      menuItem('View All Requests','/request/view/all',3);
      menuItem('View My Requests','/request/view/mine',1);
  print '  </div>
  </div>'; }
  // Fulfillment
  menuItem('Fulfillment','/fulfillment',2);
  // User
  if ($f3->get('SESSION.groupId') >= 3) {
  print '
    <div class="ui dropdown item">User
    <i class="dropdown icon"></i>
    <div class="menu">';
      menuItem('New User','/user/new',3);
      menuItem('Import Users','/user/import',3);
  print '  </div>
  </div>'; }
  $name = $f3->get('SESSION.name');
  $room = $f3->get('SESSION.room');
  print <<<HTML
  <div class="right menu">
    <div class="ui dropdown item"><i class="map icon"></i>   Room $room
      <i class="dropdown icon"></i>
      <div class="menu">
        <a href="/api/switchRoom/100" class="item">Switch to studio</a>
        <a href="/api/switchRoom/121" class="item">Switch to classroom</a>
      </div>
    </div>
    <div class="ui item"><i class="user icon"></i>   $name</div>
    <a class="ui item" href="/logout/user">
      Logout
    </a>
    <script>$('.ui.dropdown.item').dropdown();</script>
  </div>
</div>
</div>
<div class="page" id="page" style="/* display:none; */">
<div class="ui main text container" style="margin-top:2em;">
<div class="ui center aligned">
HTML;
}
?>
