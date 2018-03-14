<?php
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}
?>
<h4 class="ui horizontal divider header">
      Pending Reqests
</h4>
<div class="ui cards">
    <?php
    $db = new \DB\SQL($f3->get("dbconn"), $f3->get("dbuser"), $f3->get("dbpass"));
    $mapper = new DB\SQL\Mapper($db, 'Requests');
    $mapper->load(array('status=?','new'));
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
          <a href="/api/approveProj/'.$mapper->rid.'?r='.urlencode($f3->get('PATH')).'" class="ui basic green button">Approve</a>
          <div class="ui basic red button disabled" data-content="Currently denying requests is not supported">Deny</div>
        </div>
      </div>
      </div>
';
        $mapper->next();
    }
    ?>
</div>
<h4 class="ui horizontal divider header">
      Approved Requests (Have Not Filmed)
</h4>
<div class="ui cards">
<?php $mapper->load(array('status=?','approved'));
while (!$mapper->dry()) {
    print '
      <a class="ui card" href="/request/view/'.$mapper->rid.'">
        <div class="content">
          <div class="header">
            '.$mapper->proj.'
          </div>
          <div class="meta">'.$mapper->projType.'</div>
          <div clas="description">
            <b>Approved:</b> '.$mapper->name.' will film '.$mapper->pdOut.' on '.$mapper->dateOut.'.
          </div>
        </div>
      </a>';
      $mapper->next();
}
    ?>
    </div>
<script>
    $('.button.disabled')
    .popup()
    ;
</script>
