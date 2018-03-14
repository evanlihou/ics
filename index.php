<?php
require_once __DIR__ . '/vendor/autoload.php';
$f3=require('lib/base.php');
require('header.php');
$client = new Raven_Client('https://001ecda263fa46acb94bf6df7d647c1a:be1dd1b11bac47d9919cfc48c7de0b4c@sentry.io/175856');

// If inactive for 10 minutes, log out
if ($f3->get('SESSION') && (time() - $f3->get('SESSION.timeOut')) > 600) {
  require 'logout.php';
    logout();
    $f3->reroute('/login/expired?r='.urlencode($f3->get('PATH')));
}
if ($f3->get('SESSION')) $f3->set('SESSION.timeOut', time());
$group = $f3->get('SESSION.groupId');
// $log = new Logger('logs/log.txt');
// $log->pushHandler(new StreamHandler('logs/log.txt', Logger::DEBUG));

if ((float)PCRE_VERSION<7.9) {
	trigger_error('PCRE version is out of date');
}
// Load configuration
$f3->config('config.ini');

// Normalize *.php to * and redirect /index to / for backwards-compatibility
$f3->redirect('GET|HEAD /*.php', '/*');
$f3->redirect('GET|HEAD /index', '/');

// Routing
require('routeControllers/homeRoutes.php');
require('routeControllers/itemRoutes.php');
require('routeControllers/requestRoutes.php');
require('routeControllers/userRoutes.php');
require('routeControllers/crewRoutes.php');

$f3->route('GET /api/@action/@id',
  function($f3) {
    $view=new View;
    echo $view->render('api.php');
  }
);

// Error handler
$f3->set('ONERROR',
  function($f3){
  require 'error.php';
  errorMsg(
    $f3->get('ERROR.code'),
    $f3->get('ERROR.status'),
    $f3->get('ERROR.text'),
    $f3->get('ERROR.trace')
  );
});

//Access Control
  $access=Access::instance();
  $user = $access->authorize($group);
  require 'routeControllers/access.php';
  if (!$access->granted($f3->get('PATH'),$group)) {
    if ($f3->get('SESSION')) {
        $f3->reroute('/login/C02?r='.urlencode($f3->get('PATH')));
      } else {
      $f3->reroute('/login/C03?r='.urlencode($f3->get('PATH')));
      }
  }

  $client->user_context(array(
    'username' => $f3->get('SESSION.name'),
    'uid' => $f3->get('SESSION.uid')
  ));

  $error_handler = new Raven_ErrorHandler($client);
  $error_handler->registerExceptionHandler();
  $error_handler->registerErrorHandler();
  $error_handler->registerShutdownFunction();

$f3->run();
?>
        </div>
      </div>
    </div>
  </div>
</body>
<?php
// This has been temporarily removed until a better solution can be made
// echo (
//   $f3->get('PATH') == '/item/checkout'?'':
//   "<script>jQuery.ready($('div.page').transition('fade up'));</script>");
?>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-96360094-4', 'auto');
ga('send', 'pageview');
</script>
</html>
