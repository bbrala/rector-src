<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\Core\ValueObject\PhpVersion;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(FirstClassCallableRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_81);
};
