<?php

namespace Frames\Mist;

use Frames\Mist\Server\Server;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../example.php';

$options = getopt('w', ['watch']);


if (isset($options['w']) || isset($options['watch'])) {

    $command = __DIR__ . '/../vendor/bin/php-watcher ' . buildArgs().' '.__DIR__ . '/../bin/mist.php';

    passthru($command);
    exit;
}

Server::run();

function buildArgs(): string
{
    return implode(' ',array_map(
        function ($dirToWatch) {
            return '--watch '.escapeshellarg($dirToWatch);
        },
        Mist::config()->directories
    ));

}