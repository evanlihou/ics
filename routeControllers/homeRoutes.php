<?php
  global $f3;

  $f3->route('GET /',
  	function($f3) {
      $group = $f3->get('SESSION.groupId');
      if (!$f3->get('SESSION')) $f3->reroute('/login');
      $view=new View;
      if ($group == '3' || $group == '4') {
          makeHeader('Home', 'green');
      		echo $view->render('adminHome.php');
        } else {
          makeHeader('Home', 'black');
      		echo $view->render('userHome.php');
        }
  	}
  );
  $f3->route('GET /admin/home',
  	function($f3) {
      $view=new View;
      makeHeader('Home', 'green');
  		echo $view->render('adminHome.php');
  	}
  );
  // Login and auth
  $f3->route('GET /login',
    function($f3) {
    $view=new View;
    echo $view->render('login.php');
  }
  );
  $f3->route('GET /login/@reason',
    function($f3) {
    $view=new View;
    echo $view->render('login.php');
  }
  );
  $f3->route('POST /authenticate',
    function($f3) {
    $view=new View;
    echo $view->render('authenticate.php');
  }
  );
  $f3->route('GET|POST /logout/@reason',
    function($f3) {
      require 'logout.php';
      logout();
      $f3->reroute('/login/'.$f3->get('PARAMS.reason'));
    }
  );
?>
