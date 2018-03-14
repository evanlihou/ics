<?php
global $f3;
if (!$f3) {
    echo 'F3 not found.';
    die;
}

$requestID = $f3->get('PARAMS.rid');
$db = new \DB\SQL($f3->get("dbconn"),
    $f3->get("dbuser"),
    $f3->get("dbpass")
);

$request = new DB\SQL\Mapper($db, 'Requests');
$request->load(array('rid=?',$requestID));

if  (!$requestID) {
    echo 'There was no request specified.';
    die;
}

?>
<link rel="stylesheet" type="text/css" href="/assets/classic.date.css" id="theme_date">
<link rel="stylesheet" type="text/css" href="/assets/classic.css" id="theme_base">

<div class="ui warning message">
  <b>TODO:</b>
  <ul>
    <li>
      <s>Make fields in checkout automatically filled</s>
    </li>
    <li>
      <s>Add logging</s>
    </li>
    <li>
      <s>Make list of items to be checked out prettier (not camelCase)</s>
    </li>
    <li>
      <s>Ensure no extraneous code is left in this file</s>
    </li>
    <li>
      Get rid of transition on all page loads after first
    </li>
    <li>
      <i>Optional:</i> Add items via javascript instead of an api call (this will involve making an API call to get the item's info)
    </li>
  </ul>
</div>

<div class="ui stackable grid">
  <div class="row">
  <div class="eight wide column">
    <?php
    $itemsDict = array(
      'camera'=>'Camera',
      'tripod'=>'Tripod',
      'lights'=>'Lights',
      'cStands'=>'C-stands',
      'extensionCords'=>'Extension Cords',
      'mic'=>'Microphone(s)',
      'recorder'=>'Recorder',
      'accessories'=>'Accessories',
      'xlr'=>'XLR Cables'
    );
    ?>
    <div class="ui container">
      <table class="ui celled table">
        <?php
        foreach ($itemsDict as $k=>$v) {
                print '<tr>';
                print '<td>'.$v.'</td><td>'.$request->$k.'</td>';
                print '</tr>';
        }
        ?>
      </table>
    </div>
    <div class="ui horizontal divider">
      <i class="sticky note outline icon"></i>
      Notes
    </div>
    <div class="ui form">
      <textarea readonly><?= $request->notes ?></textarea>
    </div>
    <br />
    <br />

  </div>
  <div class="eight wide column">
    <?php

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
        print '<div class="ui message"><div class="header">Done</div></div>';
        foreach ($f3->get('idList') as $i) {
          // This should use a mapper
            $result = $sql->exec(
                "UPDATE `Inventory".$f3->get('SESSION.room')."`".
                "SET `available`='0',`inDate`='" . $f3->get('POST.dateIn').
                "',`outTo`='" . $f3->get('POST.name') . "',`inPd`='".
                $f3->get('POST.pdIn') . "' WHERE `id`='" . $i . "';"
            );

        // TODO: Use a mapper to fetch this instead.
          $oldHist = $sql->exec("SELECT `history` FROM `Inventory".$f3->get('SESSION.room')."` WHERE `id` = ".$i);
          $history = json_decode($oldHist[0]["history"], true);
          array_push(
              $history, array(
                  'date'=>date('d-m-Y'),
                  'type'=>'Check Out',
                  'desc'=>$f3->get('POST.name').' checked out this item'));
          $sql->exec("UPDATE `Inventory".$f3->get('SESSION.room')."` SET `history`='".json_encode($history)."' WHERE `ID`='" . $i . "';");
    }
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
            <select class="ui search selectable dropdown name" name="name" data-value="<?= $request->requestedBy ?>" autocomplete="off">
              <option value="">Select a Person</option>
                <?php
                    $db = new \DB\SQL($f3->get('dbconn'), $f3->get('dbuser'), $f3->get('dbpass'));
                    $mapper = new \DB\SQL\Mapper($db, 'Users');
                    $userList = $mapper->find();

                foreach ($userList as $u) {
                    echo "<option value=\"$u->uid\"> $u->name ($u->uid)</option>";
                }
                ?>
            </select>
          </div>
          <div class="field">
            <label>Expected in:</label>
            <input type="text" name="dateIn" class="datepicker cid" data-value="<?= $request->dateIn ?>" autocomplete="off">
          </div>
          <div class="field">
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
      <div class="field">
        <label>IID</label>
        <input name="id" pattern="\d*" autofocus autocomplete="off"></input>
      </div>
      <input hidden name="sIds" value="<?= ($_POST ? serialize($f3->get('idList')) : '') ?>"></input>
    <button class="ui button" <?= (!$f3->get('PARAMS.id')?'type="submit"':'') ?>>Submit</button>

    <?php
    // If there are IDs in the list, show them
    if ($f3->get('idList')) {
        $db = new \DB\SQL($f3->get('dbconn'), $f3->get('dbuser'), $f3->get('dbpass'));
        $mapper = new DB\SQL\Mapper($db, 'Inventory'.$f3->get('SESSION.room'));
        print '<br><br><div class="ui two cards">';
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
    <script src="/assets/picker.js"></script>
    <script src="/assets/picker.date.js"></script>
    <script>
      $('.datepicker').pickadate({format:'mmmm dd', formatSubmit:'yyyy-mm-dd', hiddenName: true});
      $('.ui.dropdown').dropdown({fullTextSearch:true});
      $('.ui.dropdown.name').dropdown('set selected','<?= ($f3->get('POST')? $f3->get('POST.name'):$request->requestedBy)?>');
      $('.ui.dropdown.pd').dropdown('set selected','<?= ($f3->get('POST')? $f3->get('POST.pdIn'):$request->pdIn)?>');
      $('.ui.dropdown.room').dropdown('set selected','<?php ($f3->get('POST')? $f3->get('POST.room'):'')?>');
    </script>
    </div>
</div>
</div>
