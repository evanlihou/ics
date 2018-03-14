<?php
global $f3;

$f3->route('GET|POST /request/new',
	function($f3) {
    makeHeader('Submit Request', 'black');
    $view=new View;
		echo $view->render('submitRequest.php');
	}
);
$f3->route('POST /request/new/video',
	function($f3) {
    makeHeader('Submit Request', 'black');
    $view=new View;
		echo $view->render('requestVisual.php');
	}
);
$f3->route('POST /request/new/audio',
	function($f3) {
    makeHeader('Submit Request', 'black');
    $view=new View;
		echo $view->render('requestAudio.php');
	}
);
$f3->route('POST /request/new/review',
	function($f3) {
    makeHeader('Submit Request', 'black');
    $view=new View;
		echo $view->render('requestReview.php');
	}
);
$f3->route('GET|POST /request/view/all',
	function($f3) {
    makeHeader('Requests', 'green');
    $view=new View;
		echo $view->render('requestViewAll.php');
	}
);
$f3->route('GET|POST /request/view/@id',
	function($f3) {
    if (!$f3->get('GET.noHeader')) makeHeader('Requests', 'green');
    $view=new View;
		echo $view->render('requestViewOne.php');
	}
);
$f3->route('GET|POST /request/view/mine',
  function($f3) {
    makeHeader('My Requests', 'black');
    $view=new View;
		echo $view->render('requestViewMine.php');
	}
);
//Fulfillment
$f3->route('GET|POST /fulfillment',
	function($f3) {
    makeHeader('Fulfillment', 'blue');
    $view=new View;
		echo $view->render('fulfillment.php');
	}
);
$f3->route('GET|POST /fulfillment/@rid',
	function($f3) {
    makeHeader('Fulfillment', 'blue');
    $view=new View;
		echo $view->render('fulfillRequest.php');
	}
);
?>
