<form action="#" method="post">
  <input type="password" name="password" placeholder="Enter Password"></input>
  <input type="submit">
</form>
<?php
$f3=require('/var/www/html/ics/lib/base.php');
if ($_POST) {
    $crypt = \Bcrypt::instance();
    echo $crypt->hash($_POST["password"]);
}

?>
