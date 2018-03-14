<?php
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}

if ($f3->get('PARAMS.action') == 'approveProj') {
    $db = new \DB\SQL($f3->get("dbconn"), $f3->get("dbuser"), $f3->get("dbpass"));
    $mapper = new DB\SQL\Mapper($db, 'Requests');
    $mapper->load(array('rid=?',$f3->get('PARAMS.id')));
    $mapper->status = 'approved';
    $mapper->save();
    $f3->reroute(urldecode($f3->get('GET.r')));
}

if ($f3->get('PARAMS.action') == 'switchRoom') {
    $newRoom = $f3->get('PARAMS.id');
    $f3->set('SESSION.room', $newRoom);
    $f3->reroute('/');
}

if ($f3->get('PARAMS.action') == 'checkout') {
    include '../lib/checkout.php';
    checkout($f3->get('PARAMS.id'));
}
