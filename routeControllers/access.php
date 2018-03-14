<?php
global $access;
global $f3;
$access->policy('deny');

$access->allow('/');
$access->allow('*', '3,4');

$access->deny('/dev*', '3');

$access->allow('/login*', '*');
$access->allow('/logout*', '*');
$access->allow('/authenticate', '*');

$access->allow('/request/new*', '1,2');
$access->allow('/request/view/mine', '1,2');
$access->allow('/fulfillment*', '2');

$access->allow('/item/checkin*', '2');
$access->allow('/item/checkout*', '2');
$access->allow('/item/checkout*', '2');

$access->allow('/api*', '1,2');
