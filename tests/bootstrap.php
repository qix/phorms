<?php
require __DIR__.'/../sample/vendor/autoload.php';

// Set up a secret for csrf tokens
Phorms\Csrf::setSecret('secret');

// Set up a fake REQUEST_URI
Phorms\Request::setRequestUri('/phpunit-test');
