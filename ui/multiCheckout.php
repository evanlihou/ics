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

// If something was already posted to the page, copy the previous IDs over
if ($f3->get('POST')) {
    $f3->set('idList', unserialize($_POST["sIds"]));
}

//Function declarations
function addItem(int $itemId)
{
    global $f3;
    $idList = $f3->get('idList');
    if (!$idList) {
        $f3->set('idList', array());
    }
    $f3->push('idList', $itemId);
}

function done()
{
    global $f3;
    $sql = new \DB\SQL(
        $f3->get('dbconn'),
        $f3->get('dbuser'),
        $f3->get('dbpass'));
    print '<div class="ui message"><div class="header">Done</div>';
    foreach ($f3->get('idList') as $i) {
      // This should use a mapper
        $result = $sql->exec(
            "UPDATE `Inventory".$f3->get('SESSION.room')."`".
            "SET `available`='0',`inDate`='" . $f3->get('POST.dateIn').
            "',`outTo`='" . $f3->get('POST.name') . "',`inPd`='".
            $f3->get('POST.pdIn') . "' WHERE `id`='" . $i . "';"
        );
    // The following lines will be used to write this to the item's history.
    // TODO: Use a mapper to fetch this instead.
    // Actually, TODO: Fix this whole file
      // $oldHist = $sql->exec(...CODE HERE...);
      // $history = json_decode($oldHist[0]["history"], true);
      // array_push(
          // $history, array(
              // 'date'=>date(d-m-Y),
              // 'type'=>'Check Out',
              // 'desc'=>$f3->get('POST.name').' checked out this item'));
      // $db->exec("UPDATE `Inventory` SET `history`='".json_encode($history)."' WHERE `ID`='" . $i . "';");
        if ($f3->get('debug') == 3) {
            print "<li>Item checked out: ".$i.'</li>';
        }
    }
    print '</ul></div>';
    $f3->clear('POST');
}

// When the user clicks a 'checkout' link
if ($f3->get('PARAMS.id')) {
    addItem($f3->get('PARAMS.id'));
}

// When the user clicks done
if ($f3->get('POST.done')) {
    done();
}

// When the user enters an ID
if ($f3->get('POST.id')) {
    addItem($f3->get('POST.id'));
}

if ($_POST) {
    $dateIn = $f3->get('POST.dateIn');
} else {
    $dateIn = date("Y-m-d", strtotime("+1days"));
}
?>

<form method="post" action="#" class="ui form">
      <div class="field">
        <label>Name</label>
        <select class="ui search selectable dropdown name" name="name" data-value="<?= ($_POST ? $f3->get('POST.name') : '')?>" autocomplete="off">
          <option value="">Select a Name</option>
            <?php
                $db = new \DB\SQL($f3->get('dbconn'), $f3->get('dbuser'), $f3->get('dbpass'));
                $mapper = new \DB\SQL\Mapper($db, 'Users');
                $userList = $mapper->find();

            foreach ($userList as $u) {
                echo "<option value=\"$u->uid\">$u->name ($u->uid)</option>";
            }
            ?>
        </select>
      </div>
    <div class="two fields">
      <div class="field">
        <label>Expected in:</label>
        <input type="text" name="dateIn" class="datepicker cid" data-value="$dateIn" autocomplete="off">
      </div>
      <div class="field">
        <label>&nbsp;</label>
        <select class="ui search selectable dropdown pd" name="pdIn" autocomplete="off">
          <option value="">Select Period</option>
            <?php
            $enumList = explode(",", "Before Homeroom,Homeroom,1st Period,2nd Period,3rd Period,4th Period,5th Period,6th Period,7th Period,8th Period,After School");

            foreach ($enumList as $value) {
                echo "<option value=\"$value\">$value</option>";
            }
            ?>
        </select>
      </div>
    </div>
  <div class="field">
    <label>IID</label>
    <input name="id" pattern="\d*" autofocus <?= ($f3->get('PARAMS.id') ? 'disabled=""' : '') ?> autocomplete="off"></input>
  </div>
  <input hidden name="sIds" value="<?= ($_POST ? serialize($f3->get('idList')) : '') ?>"></input>
<button class="ui button" <?= (!$f3->get('PARAMS.id')?'type="submit"':'') ?>>Submit</button>

<?php
// If there are IDs in the list, show them
if ($f3->get('idList')) {
    $db = new \DB\SQL($f3->get('dbconn'), $f3->get('dbuser'), $f3->get('dbpass'));
    $mapper = new DB\SQL\Mapper($db, 'Inventory'.$f3->get('SESSION.room'));
    print '<br><br><div class="ui three cards">';
    foreach ($f3->get('idList') as $i) {
        $item = $mapper->load(array('id=?',$i));
        if ($item) {
            print '<div class="card"><div class="content">
      <div class="header">'.$item['model'].'</div>
      <div class="content">IID: '.$item['id'].'<br>Notes: '.$item['notes'].'</div>
  </div></div>';
        }
        if ($mapper->dry()) {
            print '<div class="red card">
      <div class="content"><div class="middle floated header"><i class="warning sign icon"></i></div>IID '.$i.' does not exist in the database.
  </div></div>';
        }
    }
    print '</div><br><br><button class="ui button" type="submit" name="done" value="done">Done</button>';
}
?>
</form>
<?php
if (!$f3->get('POST') && !$f3->get('PARAMS.id')) {
    print "<script>$(document).ready($('div.page').transition('fade up'));</script>";
} elseif (!$f3->get('PARAMS.id')) {
    print "<script>$(document).ready($('div.page').transition('fade up',0));</script>";
}
?>

<script>
  $('.datepicker').pickadate({format:'mmmm dd', formatSubmit:'yyyy-mm-dd', hiddenName: true});
  $('.ui.dropdown').dropdown({fullTextSearch:true});
  $('.ui.dropdown.name').dropdown('set selected','<?= ($f3->get('POST')? $f3->get('POST.name'):'')?>');
  $('.ui.dropdown.pd').dropdown('set selected','<?= ($f3->get('POST')? $f3->get('POST.pdIn'):'')?>');
  $('.ui.dropdown.room').dropdown('set selected','<?php ($f3->get('POST')? $f3->get('POST.room'):'')?>');
</script>
