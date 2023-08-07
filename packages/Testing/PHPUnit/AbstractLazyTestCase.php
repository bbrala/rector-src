<?php

declare(strict_types=1);

namespace Rector\Testing\PHPUnit;

use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;
use Rector\Core\DependencyInjection\LazyContainerFactory;

abstract class AbstractLazyTestCase extends TestCase
{
    private static ?Container $container = null;

    protected static function getContainer(): Container
    {
        if (! self::$container instanceof Container) {
            $lazyContainerFactory = new LazyContainerFactory();
            self::$container = $lazyContainerFactory->create();
        }

        return self::$container;
    }

    /**
     * @template TType as object
     * @param class-string<TType> $class
     * @return TType
     */
    protected function make(string $class): object
    {
<<<<<<< HEAD
        return self::getContainer()->make($class);
    }

    protected static function getContainer(): Container
    {
        if (! self::$container instanceof Container) {
            $lazyContainerFactory = new LazyContainerFactory();
            self::$container = $lazyContainerFactory->create();
        }

        return self::$container;
=======
        $container = self::getContainer();
        return $container->make($class);
>>>>>>> 2958bc3d19 (refactor RectorConfig to Laravel container)
    }
}
