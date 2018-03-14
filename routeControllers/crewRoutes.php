<?php
  global $f3;
  $f3->route('GET /crews/new',
  	function($f3) {
      $view=new View;
      makeHeader('Crews', 'green');
  		echo $view->render('newCrew.php');
  	}
  );
?>
