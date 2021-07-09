<?php

declare(strict_types=1);

namespace Pollen\Faker;

use Pollen\Container\ServiceProvider;

class FakerServiceProvider extends ServiceProvider
{
    protected $provides = [
        FakerInterface::class
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share(FakerInterface::class, function () {
            return new Faker([], $this->getContainer());
        });
    }
}
