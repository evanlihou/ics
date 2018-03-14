<?php
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}
$id = $f3->get('PARAMS.id');
$db = new \DB\SQL($f3->get('dbconn'), $f3->get('dbuser'), $f3->get('dbpass'));
$row = $db->exec(
  "SELECT `*` FROM `Inventory".$f3->get('SESSION.room')."` WHERE `id` = " . $id . " UNION SELECT `*` FROM `Inventory121` WHERE `id` = " . $id . ";"
);
$lexAvail = array(
  '0' => 'unavailable',
  '1' => 'available'
);
$lexHistory = array(
  'Check Out' => 'sign out',
  'Check In' => 'sign in',
  'Note' => 'edit',
  'created' => 'plus'
);
$history = array_reverse(json_decode($row[0]["history"], true));
?>
<div class="ui stackable grid"><div class="ten wide column">
  <h4 class="ui horizontal divider header">
      Info
  </h4>
  <p>Name: <?=$row[0]["model"]?></p>
  <p>Available:
<?php if ($row[0]["available"] == "0") {
    print "<span class=\"red\">Unavailable</span>";
} elseif ($row[0]["available"] == "1") {
    print '<span class="green">Available</span>';
}?></p>
  <p>Last checked out to: <?=$row[0]["outTo"]?></p>
  <h4 class="ui horizontal divider header">
      Notes
  </h4>
  <!-- Hard coded notes example -->
  <div class="ui feed">
  <div class="event">
    <div class="label">
      <i class="note icon"></i>
    </div>
    <div class="content">
        <?=$row[0]["notes"]?>
    </div>
  </div>
</div>
<h4 class="ui horizontal divider header">
      History
  </h4>
  <div class="ui feed">
    <?php
    foreach ($history as $k => $v) {
        print '<div class="event">
        <div class="label"><i class="'.$lexHistory[$v['type']].' icon"></i></div>
        <div class="content">
          <div class="summary">'.$v['desc'].'</div>
          <div class="date">'.$v['date'].'</div>
        </div>
        </div>';
    }
    ?>
</div>
</div>
<div class="six wide column">
  <h4 class="ui horizontal divider header">
      Actions
  </h4>
  <a class="fluid ui button" href="/item/checkin/<?=$id?>">Checkin</a><br>
  <a class="fluid ui button" href="/item/checkout/<?=$id?>">Checkout</a>
</div>
</div>
