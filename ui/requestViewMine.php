<style>.clickable-row {cursor: pointer;}</style>
<?php
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}
$db = new \DB\SQL($f3->get('dbconn'), $f3->get('dbuser'), $f3->get('dbpass'));
$rows = $db->exec('SELECT * FROM `Requests` WHERE `RequestedBy` = '.$f3->get('SESSION.uid').';');
  // Table
  echo count($rows) !== 0? '<table class="ui selectable striped unstackable celled table"><thead><th>RID</th><th>Project Name</th><th>Project Type</th><th>Status</th></thead><tbody>' : '';
  // output data of each row
foreach ($rows as $rows) {
    echo '<tr><td>' . $rows["rid"] . "</td><td>".$rows["proj"]."</td><td>" . $rows["projType"]."</td><td>".ucfirst($rows["status"])."</tr>";
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
