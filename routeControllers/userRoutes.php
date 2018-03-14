<?php
global $f3;

$f3->route('GET|POST /user/import',
	function($f3) {
    makeHeader('Import Users', 'green');
    $view=new View;
		echo $view->render('userImport.php');
	}
);
$f3->route('GET|POST /user/new',
	function($f3) {
    makeHeader('New User', 'green');
    $view=new View;
		echo $view->render('userNew.php');
	}
);
?>
