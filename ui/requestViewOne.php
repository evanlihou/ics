<?php global $f3; if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}

if (!$f3->get('PARAMS.id')) {
    if (!$_GET['rid']) {
      echo "No Request ID given.";
    } else {
      $rid = $_GET['rid'];
    }
} else {
  $rid = $f3->get('PARAMS.id');
}

$db = new \DB\SQL($f3->get("dbconn"),
    $f3->get("dbuser"),
    $f3->get("dbpass")
);

$mapper = new DB\SQL\Mapper($db, 'Requests');
$mapper->load(array('rid=?',$rid));

$items = array();

$gi = explode(',', 'camera,tripod,lights,cStands,extensionCords,mic,recorder,accessories,xlr');
foreach ($gi as $k) {
    $items[$k] = $mapper->$k;
}
?>

<div class="ui stackable grid">
  <div class="six wide column">
    <div class="ui segment">
      <div class="ui top attached label">
        Out
      </div>
      <p>Date: <?= $mapper->dateOut ?></p>
      <p>Period: <?= $mapper->pdOut ?></p>
    </div>
    <div class="ui segment">
      <div class="ui top attached label">
        In
      </div>
      <p>Date: <?= $mapper->dateIn ?></p>
      <p>Period: <?= $mapper->pdIn ?></p>
    </div>
  </div>
  <div class="three wide column">
    <p><b><?= $mapper->proj ?></b></p>
    <p><?= $mapper->projType ?></p>
    <br />
  </div>
  <div class="three wide column">
    <p><i class="user icon"></i><?= $mapper->name ?></p>
  </div>
</div>
<div class="ui divider"></div>
<div class="ui container">
  <table class="ui celled table">
    <?php
    foreach ($gi as $k) {
            print '<tr>';
            print '<td>'.$k.'</td><td>'.$mapper->$k.'</td>';
            print '</tr>';
    }
    ?>
  </table>
</div>
<div class="ui horizontal divider">
  <i class="sticky note outline icon"></i>
  Notes
</div>
<div class="ui form">
  <textarea readonly><?= $mapper->notes ?></textarea>
</div>
<br />
<br />
