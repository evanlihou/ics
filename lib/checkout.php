<?php
global $f3;
if (!$f3) {
    echo 'F3 not found.';
    die;
}
function checkout(int $id)
{
  $sql = new \DB\SQL(
      $f3->get('dbconn'),
      $f3->get('dbuser'),
      $f3->get('dbpass'));
      // TODO: THIS SHOULD USE A MAPPER
      // $item =
      // $item->available = 0;
      // $item->
  // Check out
  $result = $sql->exec(
    "UPDATE `Inventory".$f3->get('SESSION.room')."`".
    "SET `available`='0',`inDate`='" . $f3->get('POST.dateIn').
    "',`outTo`='" . $f3->get('POST.name') . "',`inPd`='".
    $f3->get('POST.pdIn') . "' WHERE `id`='" . $id . "';"
  );

  // Set the history
  $oldHist = $sql->exec("SELECT `history` FROM `Inventory".$f3->get('SESSION.room')."` WHERE `id` = ".$id);
  $history = json_decode($oldHist[0]["history"], true);
  array_push(
  $history, array(
  'date'=>date('d-m-Y'),
  'type'=>'Check Out',
  'desc'=>$f3->get('POST.name').' checked out this item'));
  $sql->exec("UPDATE `Inventory".$f3->get('SESSION.room')."` SET `history`='".json_encode($history)."' WHERE `ID`='" . $id . "';");
}
