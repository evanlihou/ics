<?php
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}

$list = explode(',', 'requestedBy,name,proj,projType,dateOut,pdOut,dateIn,pdIn');
foreach ($list as $k) {
    $requests = $f3->get('requests');
    if (!$requests) {
        $f3->set('requests', array());
    }
    $f3->set('requests.'.$k, $f3->get('POST.'.$k));
}
?>
<!DOCTYPE html>
    <div class="ui ordered stackable steps">
    <div class="step">
      <div class="content">
        <div class="title">Basic Info</div>
        <div class="description">Name, dates, etc.</div>
      </div>
    </div>
    <div class="active step">
      <div class="content">
        <div class="title">Visual</div>
        <div class="description">Camera, lights, etc.</div>
      </div>
    </div>
    <div class="step">
      <div class="content">
        <div class="title">Audio &amp; Misc</div>
        <div class="description">Mics and notes</div>
      </div>
    </div>
  </div>
    <?php
        //echo '<div class="ui message warning"><h1>Debug Info</h1>&mdash;$_SESSION values&mdash;<br>';
    ?>
  <!-- Content goes here -->
  <h3>Basic Info</h3>
    <form method="post" class="ui form" action="/request/new/audio" style="margin-left:30px;">
        <div class="two fields">
          <div class="field">
            <label>Camera</label>
            <select class="ui selectable dropdown" name="camera">
              <option value="">Select Camera</option>
                <?php
                $enumList = explode(",", "X70,NX5U,T3i,T5i,None");

                foreach ($enumList as $value) {
                    echo "<option value=\"$value\">$value</option>";
                }
                ?>
            </select>
          </div>
          <div class="field">
            <label>Tripod</label>
            <select class="ui selectable dropdown" name="tripod">
              <option value="">Select tripod</option>
                <?php
                $enumList = explode(",", "Standard,Heavy Duty,None");

                foreach ($enumList as $value) {
                    echo "<option value=\"$value\">$value</option>";
                }
                ?>
            </select>
          </div>
        </div>
        <div class="one field">
          <div class="field">
            <label>Lights</label>
            <select class="ui multiple selectable dropdown" name="lights">
              <option value="">Select lights</option>
                <?php
                $enumList = explode(",", "LED Kit,Can,Spotlight,Bounce Board,None");

                foreach ($enumList as $value) {
                    echo "<option value=\"$value\">$value</option>";
                }
                ?>
            </select>
          </div>
      </div>
      <div class="two fields">
          <div class="field">
              <label>Number of C-Stands</label>
              <input  type="text" name="cStands" placeholder="" value="">
          </div>
          <div class="field">
            <label>Number of Extension Cords</label>
            <input  type="text" name="extensionCords" placeholder="" value="">
          </div>
        </div>
        <input hidden="true" name="requests" value='<?php print(serialize($f3->get('requests'))); ?>'>
        <button class="ui button" type="submit">Submit</button>
        <!-- <div class="ui error message"></div> -->
        <script>
            $('.ui.form')
              .form({
                fields: {
                  camera         : 'empty',
                  tripod         : 'empty',
                  lights         : 'empty',
                  cStands        : 'empty',
                  extensionCords : 'empty',
                }
              })
            ;
          </script>
      <script>$('.ui.dropdown').dropdown();$('.ui.dropdown.addable').dropdown({allowAdditions: true,  hideAdditions: false});</script>
      </form>
    <br><br>
</body>
</html>
