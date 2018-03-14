<?php
$id = '';
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}
if ($f3->get('POST')) {
    $id = $f3->get('POST.id');
}
$lexAvail = array(
  '0' => 'unavailable',
  '1' => 'available'
);
?>
<form method="post" class="ui form">
              <div class="required field">
                <label>IID</label>
                <input type="text" pattern="\d*" name="id" placeholder="Enter IID" value="<?= $id ?>" autofocus autocomplete='off'>
              </div>
              <button class="ui button" type="submit">Submit</button>
              <div class="ui error message"></div>
            </form>
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
            </script>
            <br><br>
            <?php
            if ($f3->get('POST')) {
                $db = new \DB\SQL($f3->get('dbconn'), $f3->get('dbuser'), $f3->get('dbpass'));
                $row = $db->exec("SELECT `*` FROM `Inventory".$f3->get('SESSION.room')."` WHERE `id` = " . $id . ";");
                if (count($row) == 1) {
                    echo '
                        <h3>
                        <i class="info icon"></i>
                        IID '.$id.' is '.$lexAvail[$row[0]["available"]].
                        (($row[0]["available"] == 0 )?'. It is checked out to "'.$row[0]["outTo"].'"':"").
                        '.</h3></div>';
                } else {
                    echo '
                        <h3 class="center aligned">
                          <i class="warning sign icon"></i>
                          IID ' . $id . ' does not exist.
                        </h3></div>';
                }
            }
            ?>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
