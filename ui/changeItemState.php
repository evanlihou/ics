
<?php
$id = '';
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}
global $logger;

if ($f3->get('PARAMS.id')) {
    $id = $f3->get('PARAMS.id');
}
?>
<form method="post" class="ui form" action="#">
  <div class="required field">
      <label>IID</label>
      <input  type="text" pattern="\d*" name="id" placeholder="Enter IID" value="<?= $id ?>" autocomplete='off' autofocus tabindex="1">
  </div>
  <div class="inline fields">
    <div class="field">
    <div class="ui radio checkbox">
      <input name="action" value="in" type="radio" class="hidden" checked tabindex="2">
      <label>Check In</label>
    </div>
    </div>
    <div class="field">
    </div>
  </div>
  <br>
  <div class="form-group">
  <input type="submit" class="ui button" value="Submit" />
  <div class="ui error message"></div>
  </div>
   <script>
  $('.ui.form')
    .form({
      fields: {
        number: {
          identifier  : 'id',
          rules: [
            {
              type   : 'number',
              prompt : 'Please enter a valid IID'
            }
          ]
        },
                  }
    });
     $('.ui.radio.checkbox')
        .checkbox()
      ;
  </script>
</form>
  <br><br>
    <?php
    if ($_POST && empty($f3->get('POST.id'))) {
        echo "<script>alertify.error('An IID is required.', 10)</script>";
    }
    if ($_POST && !empty($f3->get('POST.id'))) {
        $debug = $f3->get('DEBUG');
        $id = $f3->get('POST.id');
        if ($debug == 4) {
            echo $id . "<br>";
            echo "Action: " . $f3->get('POST.action') . "<br>";
            echo $action;
        }
        if ($f3->get('POST.action') == 'in') {
            $action = 1;
        }
        if ($f3->get('POST.action') == 'out') {
            $action = 0;
        }
    // Connect to DB
        $db = new \DB\SQL($f3->get('dbconn'), $f3->get('dbuser'), $f3->get('dbpass'));
        $rows = $db->exec("SELECT `*` FROM `Inventory".$f3->get('SESSION.room')."` WHERE `id` = " . $id . ";");
        if (count($rows) > 1) {
            echo "<script>alertify.error('There is more than one item with an IID of ".
            $id . ". This error has been logged.', 10)</script>";
            $logger->write("User tried to change the status of IID ".$id." and more than one was returned.");
        }
        if ($debug == 4) {
            print_r($rows);
        }
        if ($action == $rows[0]["available"]) {
            die("<script>alertify.error('Unable to change status: IID ".
            $id . " is already " . $f3->get('POST.action') . "', 10);</script>");
        }
    // Set availability
        if ($f3->get('POST.action') == "in") {
          $oldHist = $db->exec("SELECT `history` FROM `Inventory".$f3->get('SESSION.room')."` WHERE `id` = ".$id);
          $history = json_decode($oldHist[0]["history"], true);
          array_push(
              $history, array(
                  'date'=>date('d-m-Y'),
                  'type'=>'Check In',
                  'desc'=>'This item was checked in'));
              $db->exec("UPDATE `Inventory".$f3->get('SESSION.room')."` SET `history`='".json_encode($history)."' WHERE `ID`='" . $id . "';");
            $rows = $db->exec(
              "UPDATE `Inventory".$f3->get('SESSION.room')."` SET `available` = '" . $action . "' WHERE `Inventory".$f3->get('SESSION.room')."`.`id` = " . $id . ";"
            );
        }
    // Print availability to check it went through
        $rows = $db->exec("SELECT `available` FROM `Inventory".$f3->get('SESSION.room')."` WHERE `id` = " . $id . ";");
        if (count($rows) == 1) {
            if ($rows[0]["available"] == 0) {
                $status = "unavailable";
            }
            if ($rows[0]["available"] == 1) {
                $status = "available";
            }
            if ($status == "available") {
                echo "<script>alertify.success('IID " . $id . " is now " . $status . "',10)</script>";
            }
            if ($status == "unavailable") {
                echo "<script>alertify.success('IID " . $id . " is now " . $status . "',10)</script>";
            }
        }
        if (count($rows) <= 0) {
            echo "<script>alertify.error('Unable to change status: IID " . $id . " does not exist.', 10)</script>";
        }
        if ($f3->get('POST.action') == "out") {
            header( 'Location: singleCheckout/' . $id );
        }
        if ($debug == 4) {
            print $db->log();
        }
    }
    ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
