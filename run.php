<?php

namespace Frames\Mist;

use Frames\Mist\Server\Server;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/mist.php';


$mocks = Mist::getInstance()->getMocks();
$config = Mist::getInstance()->getConfig();

Server::run($config, $mocks);
