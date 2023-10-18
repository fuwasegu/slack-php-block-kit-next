<?php

declare(strict_types=1);

use PhpCsFixer\Finder;
use YumemiInc\PhpCsFixerConfig\Config;

return (new Config(allowRisky: false))
    ->setFinder(
        (new Finder())
            ->in(__DIR__),
    );
