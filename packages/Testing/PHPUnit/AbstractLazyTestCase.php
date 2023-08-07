<?php

declare(strict_types=1);

namespace Rector\Testing\PHPUnit;

use PHPUnit\Framework\TestCase;
use Rector\Config\RectorConfig;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\DependencyInjection\LazyContainerFactory;
use Rector\Core\Util\Reflection\PrivatesAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Webmozart\Assert\Assert;

abstract class AbstractLazyTestCase extends TestCase
{
    private static ?RectorConfig $container = null;

    /**
     * @template TType as object
     * @param class-string<TType> $class
     * @return TType
     */
    protected function make(string $class): object
    {
        return self::getContainer()->make($class);
    }

    /**
     * @param string[] $configFiles
     */
    protected function bootFromConfigFiles(array $configFiles): void
    {
        $container = self::getContainer();
        foreach ($configFiles as $configFile) {
            $configClosure = require $configFile;
            Assert::isCallable($configClosure);

            $configClosure($container);
        }
    }

    protected static function getContainer(): RectorConfig
    {
        if (! self::$container instanceof RectorConfig) {
            $lazyContainerFactory = new LazyContainerFactory();
            self::$container = $lazyContainerFactory->create();
        }

        return self::$container;
    }

    protected function forgetRectorsRules()
    {
        $container = self::getContainer();

        $privatesAccessor = new PrivatesAccessor();

        $rectors = $container->tagged(RectorInterface::class);
        foreach ($rectors as $rector) {
            $container->forgetInstance(get_class($rector));

            $privatesAccessor->propertyClosure($container, 'tags', function ($tags) use ($rector) {
                unset($tags[RectorInterface::class]);
                unset($tags[PhpRectorInterface::class]);

                return $tags;
            });
        }
    }
}
