<!DOCTYPE html>
<?php
global $f3;
if (!$f3) {echo "F3 not found. Authentication circumvention system triggered."; die;}
?>
<!-- Page-specific assets -->
<script src="/assets/picker.js"></script>
<script src="/assets/picker.date.js"></script>
<link rel="stylesheet" type="text/css" href="/assets/classic.date.css" id="theme_date">
<link rel="stylesheet" type="text/css" href="/assets/classic.css" id="theme_base">
<script src="assets/alertify.js"></script>
<link rel="stylesheet" type="text/css" href="/assets/css/alertify.css">

<div class="ui middle aligned">
  <div class="ui ordered stackable steps">
    <div class="active step">
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
    <div class="step">
      <div class="content">
        <div class="title">Audio &amp; Misc</div>
        <div class="description">Mics and notes</div>
      </div>
    </div>
    </div>
  </div>
<!-- Content goes here -->
<h3>Basic Info</h3>
  <form method="post" class="ui form" action="/request/new/video" style="margin-left:30px;">
      <div class="two fields">
        <div class="field">
            <label>Equipment Manager's Name</label>
            <input  type="text" name="name" placeholder="First and Last" value="<?= $f3->get('SESSION.name') ?>" readonly>
            <input type="hidden" name="requestedBy" readonly value="<?= $f3->get('SESSION.uid') ?>">
        </div>
      </div>
      <div class="two fields">
        <div class="field">
            <label>Project</label>
            <input  type="text" name="proj" placeholder="" value="">
        </div>
        <div class="field">
          <label>Project Type</label>
          <select class="ui search selectable dropdown addable" name="projType">
            <option value="">Select Type</option>
            <option value="ENG">ENG</option>
            <option value="VOSOT">VOSOT</option>
            <option value="Show Open">Show Open</option>
            <option value="Short Film">Short Film</option>
            <option value="Music Video">Music Video</option>
            <option value="Crew">Crew</option>
            <option disabled="disabled">(If your choice is not shown here, type it in the search box)</option>
          </select>
        </div>
      </div>
        <div class="two fields">
          <div class="field" style="position:relative;">
            <label>Check-out Date</label>
            <input type="text" name="dateOut" class="datepicker cod" data-value="<?php echo date("Y-m-d", strtotime("+1days"));?>">
          </div>
          <div class="field">
            <label>Check-out Period</label>
            <select class="ui search selectable dropdown cop" name="pdOut">
              <option value="">Select Period</option>
              <?php
              $enumList = explode(",", "Before Homeroom,Homeroom,1st Period,2nd Period,3rd Period,4th Period,5th Period,6th Period,7th Period,8th Period,After School");

               foreach($enumList as $value)
                  echo "<option value=\"$value\">$value</option>";
               ?>
            </select>
          </div>
        </div>
        <div class="two fields">
          <div class="field" style="position:relative;">
            <label>Check-in Date</label>
            <input type="text"  name="dateIn" class="datepicker cid" data-value="<?php echo date("Y-m-d", strtotime("+1days"));?>">
          </div>
          <div class="field">
            <label>Check-in Period</label>
            <select class="ui search selectable dropdown cip" name="pdIn">
              <option value="">Select Period</option>
              <?php
              $enumList = explode(",", "Before Homeroom,Homeroom,1st Period,2nd Period,3rd Period,4th Period,5th Period,6th Period,7th Period,8th Period,After School");

               foreach($enumList as $value)
                  echo "<option value=\"$value\">$value</option>";
               ?>
            </select>
          </div>
        </div>
      <button class="ui button" type="submit">Submit</button>
      <div class="ui error message"></div>
    <script>
      $('.ui.form')
        .form({
          fields: {
            name     : 'empty',
            proj     : 'empty',
            projType : 'empty',
            pdIn     : 'empty',
            pdOut    : 'empty',
          }
        })
      ;
    </script>
    <script>
      $('.ui.dropdown').dropdown();
      $('.ui.dropdown.addable').dropdown({allowAdditions: true,  hideAdditions: false});
      $('.datepicker').pickadate({format:'mmmm dd', formatSubmit:'yyyy-mm-dd', hiddenName: true})
    </script>
    </form>
