<?php
global $f3;
if (!$f3) {
    echo "F3 not found. Authentication circumvention system triggered.";
    die;
}
foreach (unserialize($f3->get('POST.requests')) as $k => $v) {
    $f3->set('requests.'.$k, $v);
}
$list = explode(',', 'camera,tripod,lights,cStands,extensionCords');
foreach ($list as $k) {
    $requests = $f3->get('requests');
    $f3->set('requests.'.$k, $f3->get('POST.'.$k));
}
// Debug printing
?>
<!DOCTYPE html>
    <div class="ui ordered stackable steps">
    <div class="step">
      <div class="content">
        <div class="title">Basic Info</div>
        <div class="description">Name, dates, etc.</div>
      </div>
    </div>
    <div class="step">
      <div class="content">
        <div class="title">Visual</div>
        <div class="description">Camera, lights, etc.</div>
      </div>
    </div>
    <div class="active step">
      <div class="content">
        <div class="title">Audio &amp; Misc</div>
        <div class="description">Mics and notes</div>
      </div>
    </div>
  </div>
  <h3>Basic Info</h3>
    <form method="post" class="ui form" action="/request/new/review" style="margin-left:30px;">
        <div class="two fields">
          <div class="field">
            <label>Microphone</label>
            <select class="ui selectable dropdown" name="mic">
              <option value="">Select Microphone</option>
                <?php
                $enumList = explode(",", "Handheld,Lavelier,DSLR Mounted,Blimp,Røde,None");

                foreach ($enumList as $value) {
                    echo "<option value=\"$value\">$value</option>";
                }
                ?>
            </select>
          </div>
          <div class="field">
            <label>Audio Recorder</label>
            <select class="ui selectable dropdown" name="recorder">
              <option value="">Select Audio Recorder</option>
                <?php
                $enumList = explode(",", "Basic Tascam,Advanced Tascam,None");

                foreach ($enumList as $value) {
                    echo "<option value=\"$value\">$value</option>";
                }
                ?>
            </select>
          </div>
        </div>
        <div class="two fields">
          <div class="field">
            <label>Accessories</label>
            <select class="ui multiple selectable dropdown" name="accessories">
              <option value="">Select Accessories</option>
                <?php
                $enumList = explode(",", "None,Wind Screen,Pop Filter,V/O Booth");

                foreach ($enumList as $value) {
                    echo "<option value=\"$value\">$value</option>";
                }
                ?>
            </select>
          </div>
          <div class="field">
            <label>XLR(s)</label>
            <input type="text" name="xlr" placeholder="0">
          </div>
      </div>
      <div class="field">
        <label>Notes</label>
        <textarea name="notes" placeholder="Anything else?"></textarea>
      </div>
      <input hidden="true" name="requests" value='<?php print(serialize($f3->get('requests'))); ?>'>
        <button class="ui button" type="submit">Submit</button>
        <!-- <div class="ui error message"></div> -->
        <script>
          $('.ui.form')
            .form({
              fields: {
                mic          : 'empty',
                recorder     : 'empty',
                accessories  : 'empty',
                xlr          : 'empty',
              }
            })
          ;
          $('.ui.dropdown').dropdown();
          $('.ui.dropdown.addable').dropdown({allowAdditions: true,  hideAdditions: false});
          $('.ui.multiple.dropdown').dropdown({allowReselection: true});
    </script>
      </form>
    <br><br>
</body>
</html>
