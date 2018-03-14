<?php
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}
foreach (unserialize($f3->get('POST.requests')) as $k => $v) {
    $f3->set('requests.'.$k, $v);
}
$list = explode(',', 'mic,recorder,accessories,xlr,notes');
foreach ($list as $k) {
    $requests = $f3->get('requests');
    $f3->set('requests.'.$k, $f3->get('POST.'.$k));
}
?>
<table class="ui basic celled table">
  <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>
    <?php
    foreach ($f3->get('requests') as $k => $v) {
        print "<tr><td>".$k."</td><td>".$v."</td></tr>";
    }
    ?>
</tbody></table>
<?php
$db = new \DB\SQL($f3->get("dbconn"), $f3->get("dbuser"), $f3->get("dbpass"));
$mapper = new DB\SQL\Mapper($db, 'Requests');
$mapper->copyFrom('requests');
$mapper->save();
?>
<div class="ui positive message">
  <div class="content">
    Your request has been saved. For reference, your Request ID is <?= $mapper->rid ?>
  </div>
</div>
