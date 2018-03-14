<style>.clickable-row {cursor: pointer;}</style>
<?php
global $f3;
if (!$f3) {
      echo "F3 not found. Authentication circumvention system triggered.";
      die;
}
$db = new \DB\SQL($f3->get('dbconn'), $f3->get('dbuser'), $f3->get('dbpass'));
$rows = $db->exec('SELECT * FROM `Inventory'.$f3->get('PARAMS.room').'`');
$lexAvail = array(
  '0' => 'Unavailable',
  '1' => 'Available'
);
// Table
echo '<table class="ui selectable striped unstackable celled table"><thead><th>IID</th><th>Model</th><th>Status</th><th>Last checked out to</th></thead><tbody>';
// output data of each row
foreach ($rows as $rows) {
    if ($rows["out to"]) {
        $user = $db->exec('SELECT `name` FROM `Users` WHERE `uid` = '.$rows["out to"]);
    } else {
        $rows["out to"] = 0;
        $user = array(array('name'=>'Unknown'));
    }
    echo '<tr class="clickable-row" data-href="/item/info/'.$rows["id"].'?a"><td>' . $rows["id"]. '</td><td>' . $rows["model"] . "</td><td>".$lexAvail[$rows["available"]]."</td><td>" . $user[0]["name"]." (".$rows["outTo"].")</td></tr>";
}
  echo "</table>";
?>
</div>
<br>
</div>
<script>
jQuery(document).ready(function($) {
  $(".clickable-row").click(function() {
    window.location = $(this).data("href");
  });
});
</script>
