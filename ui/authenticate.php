<?php
global $f3;
$db = new \DB\SQL($f3->get("dbconn"), $f3->get("dbuser"), $f3->get("dbpass"));
$mapper = new DB\SQL\Mapper($db, 'Users');

$hashedPw = $mapper->load(array('user=?',$f3->get("POST.user")));
$crypt = \Bcrypt::instance();

$login_result = $crypt->verify($f3->get("POST.password"), $mapper->pass);
if ($login_result == false) {
    $plaintext_login = $mapper->pass == $f3->get("POST.password");
    if ($plaintext_login == false) {
        $f3->reroute('/login/invalid');
    }
}
if ($login_result == true || $plaintext_login == true) {
    echo "Logged in.";
    // $log->debug('User logged in.', array('username' => $mapper->user, 'uid' => $mapper->uid));
    new Session(); // temporarily make pages available
    $f3->set('SESSION.uid', $mapper->uid);
    $f3->set('SESSION.groupId', $mapper->groupId);
    $f3->set('SESSION.name', $mapper->name);
    $f3->set('SESSION.timeOut', time());
    $f3->set('SESSION.room', '100');
    echo "Session variables set.";
    if ($f3->get('POST.redirect') !== '') {
        $f3->reroute(urldecode($f3->get('POST.redirect')));
    } else {
        $f3->reroute('/');
    }
    echo "User was authenticated, but the code to take them to another page was \
    skipped.";
}
echo "End of script reached. There was an exception not caught by any 'if' \
statement to take them where they need to go.";
