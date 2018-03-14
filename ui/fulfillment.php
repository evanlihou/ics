<?php
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}
?>
  <script src="/assets/picker.js"></script>
  <script src="/assets/picker.date.js"></script>
  <link rel="stylesheet" type="text/css" href="/assets/classic.date.css" id="theme_date">
  <link rel="stylesheet" type="text/css" href="/assets/classic.css" id="theme_base">

<form class="ui form">
<?php
if (!$f3->get('GET.dateOut') && !$f3->get('GET.pdOut')) {
    print '
<div class="three fields">
  <div class="field">
        <input type="text" name="dateOut" class="datepicker cid" data-value="'.date("Y-m-d").'" autocomplete="off">
      </div>
      <div class="field">
        <select class="ui search selectable dropdown pd" name="pdOut" autocomplete="off">
          <option value="">Select Period</option>';
          $enumList = explode(",", "Before Homeroom,Homeroom,1st Period,2nd Period,3rd Period,4th Period,5th Period,6th Period,7th Period,8th Period,After School");

    foreach ($enumList as $value) {
        echo "<option value=\"$value\">$value</option>";
    }
    print '
        </select>
      </div>
  <button class="ui submit button">Query</button>
  </div>
</form>';
}

if ($f3->get('GET.dateOut')) {
  print 'This is a test to ensure the page loads correctly.';
  print '<div class="ui cards">';
    $db = new \DB\SQL($f3->get("dbconn"), $f3->get("dbuser"), $f3->get("dbpass"));
    $mapper = new DB\SQL\Mapper($db, 'Requests');
    $mapper->load(array('status=? and dateOut=? and pdOut=?', 'approved', $f3->get('GET.dateOut'), $f3->get('GET.pdOut')));
  if ($mapper->count() == 0) {
    print '<div class="ui error message>There were no requests found for that date and period.</div>';
  }
    while (!$mapper->dry()) {
        print '
    <div class="card">
      <div class="content">
        <div class="header">
        <a href="/request/view/'.$mapper->rid.'">
          '.$mapper->proj.'
        </a></div>
        <div class="meta">
          '.$mapper->projType.'
        </div>
        <div class="description">
          '.$mapper->name.' would like to request equipment for '.$mapper->pdOut.' on '.$mapper->dateOut.'.
        </div>
      </div>
      <div class="extra content">
        <div class="ui two buttons">
          <a href="/fulfillment/'.$mapper->rid.'" class="ui basic button">Check Out</a>
        </div>
      </div>
      </div>
      ';
        $mapper->next();
    }
  print '</div>';
}
?>

<script>
  $('.datepicker').pickadate({format:'mmmm dd', formatSubmit:'yyyy-mm-dd', hiddenName: true});
  $('.ui.dropdown').dropdown({fullTextSearch:true});
</script>
