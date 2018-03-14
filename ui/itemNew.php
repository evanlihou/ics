<?php
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}
?>
          <h3>Create a New Inventory Item</h3><br>
          <form method="post" class="ui form" action="?room=<?= $f3->get('PARAMS.room') ?>">
            <div class="field">
              <label>Model</label>
              <input type="text" name="model" placeholder="Sony NX5U" autofocus value="">
            </div>
            <div class="field">
              <label>Number</label>
              <input  type="text" name="number" placeholder="1" autofocus value="">
            </div>
            <div class="field">
              <label>Category</label>
              <select class="ui selectable dropdown" name="category">
                <option value="">Select Category</option>
                <?php
                $enumList = explode(",", "Camera,Microphone,Lighting,Cable,Tripod");

                foreach ($enumList as $value) {
                    echo "<option value=\"$value\">$value</option>";
                }
                ?>
              </select>
            </div>
            <div class="field">
              <label>Notes</label>
              <textarea name="notes" placeholder="Enter any notes here"></textarea>
            </div>
            <div class="field">
              <label>IID</label>
              <input  type="text" name="iid" id="iid" placeholder="Scan barcode" autofocus value="<?php ($f3->get('GET.id')?$f3->get('GET.id'):'');?>">
            </div>

            <?php
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false) {
                print <<<HTML
              <input onclick="workflow://run-workflow?name=121%20New%20Item"
              type="button" class="ui button"
              value="Scan" /><br><br>
HTML;
            }
            ?>
            <button class="ui button" type="submit">Submit</button>
            <div class="ui error message"></div>
            <script>
                $('.ui.form')
                  .form({
                    fields: {
                      model     : 'empty',
                      number    : ['empty','number'],
                      category  : 'empty',
                      iid       : 'empty'
                    }
                  })
                ;
              </script>
      </form>

<?php
if ($_POST) {
    $db = new \DB\SQL($f3->get('dbconn'), $f3->get('dbuser'), $f3->get('dbpass'));
    $exists = $db->exec("SELECT * FROM `Inventory".$f3->get('PARAMS.room')."` WHERE `id` = ".$f3->get('POST.iid'));
    if (count($exists) == 0) {
        $history = '{ "0": {
  "date": "'.date("Y-m-d").'",
  "type": "created",
  "desc": "Created by UID '.$f3->get('SESSION.uid').'"
}}';
        $response = $db->exec(
          "INSERT INTO `Inventory".$f3->get('PARAMS.room').
          "` (`ID`, `Model`, `Number`, `Category`, `notes`, `history`)".
          "VALUES ('".$f3->get('POST.iid')."', '".$f3->get('POST.model')."', '".
          $f3->get('POST.number')."', '".$f3->get('POST.category')."', '".
          $f3->get('POST.notes')."', '".$history."')");
          print '<div class="ui message">'.$f3->get('POST.model').' created with barcode #'.$f3->get('POST.iid').'.';
    } else {
        echo '<div class="ui negative message">An item already exists with that barcode. Please try another barcode.</div>';
    }
}
?>
      </div>
    </div>
  </div>

  <script>$('.ui.dropdown').dropdown();</script>
</body>
</html>
