<?php
global $f3;

$f3->route('GET|POST /item/info/@id',
	function($f3) {
    $view=new View;
    makeHeader('Item Info - '.$f3->get('PARAMS.id'), 'blue');
		echo $view->render('itemInfo.php');
	}
);
$f3->route('GET|POST /item/checkin',
	function($f3) {
    $view=new View;
    makeHeader('Check an Item In', 'blue');
		echo $view->render('changeItemState.php');
	}
);
$f3->route('GET|POST /item/checkin/@id',
	function($f3) {
    $view=new View;
    makeHeader('Check an Item In', 'blue');
		echo $view->render('changeItemState.php');
	}
);
$f3->route('GET|POST /item/checkout',
	function($f3) {
    if (!$f3->get('GET.noHeader')) makeHeader('Check Items Out', 'blue');
    $view=new View;
		echo $view->render('multiCheckout.php');
	}
);
$f3->route('GET|POST /item/checkout/@id',
	function($f3) {
    makeHeader('Check Items Out', 'blue');
    $view=new View;
		echo $view->render('multiCheckout.php');
	}
);
$f3->route('GET|POST /item/new/@room',
  function($f3) {
  makeHeader('Create Item','green');
  $view=new View;
  echo $view->render('itemNew.php');
});
$f3->route('GET|POST /item/list/@room',
  function($f3) {
  makeHeader('List Items','green');
  $view=new View;
  echo $view->render('itemList.php');
});
?>
