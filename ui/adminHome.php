<style>.clickable-row {cursor: pointer;}</style>
<?php
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}

$db = new \DB\SQL(
    $f3->get('dbconn'),
    $f3->get('dbuser'),
    $f3->get('dbpass')
);

    $rows = $db->exec(
      'SELECT * FROM `Inventory'.$f3->get('SESSION.room').'` WHERE `available` = 0 AND `category` != "Cable"'
    );

// Statistics
    echo '<div class="ui segment"><div class="ui two statistics">';
    echo '<div class="ui statistic"><div class="value">'.
      count($rows).
      '</div><div class="label">Item'.
      ((count($rows) != 1)?'s':'').
      ' checked out</div></div>';
    echo '<div class="ui statistic"><div class="value">'.
      date('n/j').
      '</div><div class="label">Today\'s date</div></div>';
    echo '</div></div>';
    if (count($rows) > 0) {
        // Table
        echo '<h3>Checked Out Equipment</h3>
              <table class="ui selectable striped unstackable celled table">
              <thead><th>IID</th><th>Model</th>
              <th>Checked out to</th><th>Expected in</th></thead><tbody>';
        // output data of each row

        foreach ($rows as $rows) {
            $oldDate = DateTime::createFromFormat('Y-m-d', $rows["inDate"]);
            $formattedDate = $oldDate->format('D, M jS');
            if ($rows["outTo"]) {
                $user = $db->exec('SELECT `name` FROM `Users` WHERE `uid` = '.$rows["outTo"]);
            } else {
                $user[0][name] = 'ERROR';
            }
            echo '<tr class="clickable-row'.
              ((strtotime(date('Y-m-d')) > strtotime($rows["inDate"])) ? ' negative':"").
              '" data-href="/item/info/'.$rows["id"].'?a"><td>'.
              $rows["id"]. '</td><td>' . $rows["model"] . "</td><td>".
              $user[0]["name"]." (".$rows["outTo"].")</td><td>".
              $formattedDate . " &mdash; " . $rows["inPd"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<h3>No items are currently checked out</h3>";
    }
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
</body>
</html>
