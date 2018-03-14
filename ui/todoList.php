<?php global $f3; if (!$f3) {echo "F3 not found. Authentication circumvention system triggered."; die;} ?>
<h4 class="ui horizontal divider header">
    To-do:
  </h4>
    <div class="ui list">
      <?php function todo (string $p, bool $s, string $m, $icon = null) {
          $iconColor = '';
          $colorMatch = array(
            'p1' => 'red',
            'p2' => 'orange',
            'p3' => 'yellow',
            'p4' => 'green',
          );
          if ($p && isset($colorMatch[$p])) $iconColor = $colorMatch[$p];
          else $iconColor = $p;
          if ($s == true) $iconColor = 'grey';
          if ($icon) $iconContent = '<i class="'.$icon.' icon" style="margin-right:0;"></i>';
          if (!$icon) $iconContent = strtoupper($p);
          if ($s == true) $iconContent = '<i class="check icon" style="margin-right:0;"></i>';
          $return = '<div class="item"><span class="ui '.$iconColor.' circular label">'.$iconContent.'</span> '.$m.'</div>';
          print $return;

        }
        todo('p2',false,'Migrate singleCheckout DB conn');
        todo('p2',false,'Migrate changeItemState DB conn');
        echo '<b>';todo('p1',false,'Migrate database connections to F3'); echo '</b>';
        todo('p3',false,'Map out workflow for selecting items');
        todo('grey',false,'Menu color key','key');
        echo '<div style="padding-left:25px;padding-bottom:10px;">';
          todo('green',false,'Admin');
          todo('blue',false,'Equipment Room');
          todo('black',false,'User');
          todo('red',false,'Error / Dead page');
        echo '</div>';
      ?>
      <div class="item"><span class="ui grey circular label"><i class="check icon" style="margin-right:0;"></i></span> Add single-item check-in/out to navbar</div>
      <div class="item"><span class="ui yellow circular label">P3</span> Sign-on page so we know who signed out what<i> &mdash; The table right above you <span class="mif-arrow-up"></span></i></div>
      <div class="item"><span class="ui yellow circular label"><i class="clock icon" style="margin-right:0;"></i></span> Interactive drop downs for all items sorted by function <i> &mdash; Actually, it'll probably be a tree with checkboxes. Maybe reserved items disabled?</i></div>
    </div>
