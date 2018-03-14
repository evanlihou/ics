<?php
global $f3;
function logout() {
    global $f3;
    $f3->clear('SESSION');
    echo 'Logged out. If you have not been redirected, click <a href="/">here to log back in</a>.';
}
?>
