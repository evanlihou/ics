<script src="/assets/picker.js"></script>
<script src="/assets/picker.date.js"></script>
<link rel="stylesheet" type="text/css" href="/assets/classic.date.css" id="theme_date">
<link rel="stylesheet" type="text/css" href="/assets/classic.css" id="theme_base">
<?php
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}
$db = new \DB\SQL($f3->get('dbconn'),$f3->get('dbuser'),$f3->get('dbpass'));
$rows = $db->exec("SELECT `*` FROM `Inventory".$f3->get('SESSION.room')."` WHERE `id` = " . $f3->get('PARAMS.id') . ";");
if (count($rows) != 0) {
    echo "<h3>Check Out " . $rows[0]["model"] . "</h3>";
}
?>
          <form method="post" class="ui form" action="<?php if ($_POST) header('Location: /changeItemState/' . $f3->get('PARAMS.id')); ?>">
            <div class="field">
              <label>Name</label>
              <input  type="text" name="outTo" placeholder="First and Last" autofocus value="">
            </div>
            <div class="field" style="position:relative;">
              <label>Check-in Date</label>
              <input type="text"  name="dateIn" class="datepicker" data-value="<?php echo date("Y-m-d", strtotime("+1days"));?>">
            </div>
            <div class="field">
              <label>Check-in Period</label>
              <select class="ui search selectable dropdown" name="pdIn">
                <option value="">Select Period</option>
                <?php
                $enumList = explode(",", "Before Homeroom,Homeroom,1st Period,2nd Period,3rd Period,4th Period,5th Period,6th Period,7th Period,8th Period,After School");

                 foreach($enumList as $value)
                    echo "<option value=\"$value\">$value</option>";
                 ?>
              </select>
            </div>
            <script>$('.ui.dropdown').dropdown();</script>
            <br>
            <button class="ui button" type="submit">Submit</button>
          </form>
          <script>$('.datepicker').pickadate({format:'mmmm dd', formatSubmit:'yyyy-mm-dd', hiddenName: true})</script>
        </div>
      <?php
        $debug = 0;
        if ($debug == 1) echo $_POST["dateIn"]."<br>";
        if ($_POST) { $rows = $db->query("UPDATE `Inventory".$f3->get('SESSION.room')."` SET `available`='0',`inDate`='" . $_POST["dateIn"] . "',`outTo`='" . $_POST["outTo"] . "',`inPd`='" . $_POST["pdIn"] . "' WHERE `id`='" . $f3->get('PARAMS.id') . "';");
        // Print availability to check it went through
        if (count($rows) > 0) {
          echo "IID " . ($_GET ? $_GET["id"]:'') . " is now signed out to " . $_POST["outTo"] . " and expected back on " . $_POST["dateIn"];
        } else {
          echo "IID " . $_POST["id"] . " does not exist.";
        } }
      ?>
      </div>
    </div>
  </div>

  <script >
    $(function(){
        $("#datepicker").datepicker();
    });
</script>
</body>
</html>
