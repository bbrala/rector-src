<?php

declare(strict_types=1);

namespace Rector\Skipper\SkipCriteriaResolver;

use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;

final class SkippedClassResolver
{
    /**
     * @var array<string, string[]|null>
     */
    private array $skippedClasses = [];

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * @return array<string, string[]|null>
     */
    public function resolve(): array
    {
        // skip cache in tests
        if ($this->skippedClasses !== [] && ! defined('PHPUNIT_COMPOSER_INSTALL')) {
            return $this->skippedClasses;
        }

        $skip = SimpleParameterProvider::provideArrayParameter(Option::SKIP);

        foreach ($skip as $key => $value) {
            // e.g. [SomeClass::class] → shift values to [SomeClass::class => null]
            if (is_int($key)) {
                $key = $value;
                $value = null;
            }

            if (! is_string($key)) {
                continue;
            }

            if (! $this->reflectionProvider->hasClass($key)) {
                continue;
            }

            $this->skippedClasses[$key] = $value;
        }

        return $this->skippedClasses;
    }
}
