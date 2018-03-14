<?php global $f3; if (!$f3) {echo "F3 not found. Authentication circumvention system triggered."; die;}?>
<form method="post" class="ui form" action="/user/new">
  <div class="field">
    <label>Name</label>
    <input type="text" name="name" placeholder="Alvin Garrett	">
  </div>
  <div class="field">
    <label>Username</label>
    <input type="text" name="user" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
  </div>
  <div class="field">
    <label>Password</label>
    <input type="password" name="pass">
  </div>
  <div class="field">
    <label>Retype Password</label>
    <input type="password" name="passconfirm">
  </div>
  <div class="field">
    <label>ID</label>
    <input name="uid" pattern="\d*" autocomplete="off" placeholder="Only fill this in if you are adding a student."></input>
  </div>
  <div class="field">
    <label>Role</label>
    <select class="ui search selectable dropdown" name="groupId" autocomplete="off">
      <option value="1">Student</option>
      <option value="2">Equipment Room</option>
      <option value="3">Teacher</option>
      <option value="4">Admin</option>
    </select>
  </div>
  <button class="ui button" type="submit">Submit</button>
<div class="ui error message"></div>
</form>
<script>
  $('.ui.form')
    .form({
      fields: {
        user        : 'empty',
        pass        : ['minLength[5]','empty'],
        passconfirm : 'match[pass]',
        name        : 'empty',
        role        : 'empty'
      }
    })
  ;
  $('.ui.dropdown').dropdown({fullTextSearch:true});
</script>

<?php

// When the user submits the form
if ($f3->get('POST')) {
  // Variable declarations
  $db = new \DB\SQL($f3->get("dbconn"),$f3->get("dbuser"),$f3->get("dbpass"));
  $mapper = new DB\SQL\Mapper($db, 'Users');
  $exists = new DB\SQL\Mapper($db, 'Users');
  $crypt = \Bcrypt::instance();

  // Mapper definitions
  $mapper->user = $f3->get('POST.user');
  $mapper->pass = $crypt->hash($f3->get('POST.pass'));
  $mapper->groupId = $f3->get('POST.groupId');
  $mapper->name = $f3->get('POST.name');
  // Check if the user already exists
  // TODO: Make separate checks for username and ID,
  // then give recommendations on how to proceed
    $exists->load(array('uid = ? OR user = ?', $f3->get('POST.uid'), $f3->get('POST.user')));
    if ($exists->dry() ) {$mapper->uid = $f3->get('POST.uid');
    } else { $error = "A user already exists with that ID or username!"; }
  if (!$error) {
    $mapper->save();
  print '<div class="ui success message"><i class="user icon"></i>User created with UID '.$mapper->uid.'</div>';
  }
  if ($error) {
    print '<div class="ui error message"><i class="warning icon"></i> '.$error.'</div>';
  }
}
?>
