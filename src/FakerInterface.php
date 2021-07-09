<?php

declare(strict_types=1);

namespace Pollen\Faker;

use Faker\Generator;
use Faker\Generator as FakerGenerator;
use Pollen\Support\Concerns\ConfigBagAwareTraitInterface;
use Pollen\Support\Proxy\ContainerProxyInterface;

/**
 * @mixin Generator
 */
interface FakerInterface extends ConfigBagAwareTraitInterface, ContainerProxyInterface
{
    /**
     * Get Faker generator instance.
     *
     * @return FakerGenerator
     */
    public function generator(): FakerGenerator;

    /**
     * Set Faker generator providers locale.
     *
     * @param string $locale
     *
     * @return static
     */
    public function setLocale(string $locale): FakerInterface;
}
