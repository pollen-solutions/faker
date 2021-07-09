<?php

declare(strict_types=1);

namespace Pollen\Faker;

use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Pollen\Support\Concerns\ConfigBagAwareTrait;
use Pollen\Support\Exception\ManagerRuntimeException;
use Pollen\Support\Proxy\ContainerProxy;
use Psr\Container\ContainerInterface as Container;

/**
 * @mixin FakerGenerator
 */
class Faker implements FakerInterface
{
    use ConfigBagAwareTrait;
    use ContainerProxy;

    /**
     * Faker main instance.
     * @var FakerInterface|null
     */
    private static ?FakerInterface $instance = null;

    /**
     * Fake data generator.
     * @var FakerGenerator|null
     */
    protected ?FakerGenerator $generator = null;

    /**
     * Faker locale providers.
     * @var string
     */
    protected string $locale = FakerFactory::DEFAULT_LOCALE;

    /**
     * @param array $config
     * @param Container|null $container
     */
    public function __construct(array $config = [], Container $container = null)
    {
        $this->setConfig($config);

        if ($container !== null) {
            $this->setContainer($container);
        }

        if (!self::$instance instanceof static) {
            self::$instance = $this;
        }
    }

    /**
     * Retrieve main class instance.
     *
     * @return static
     */
    public static function getInstance(): FakerInterface
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        throw new ManagerRuntimeException(sprintf('Unavailable [%s] instance', __CLASS__));
    }

    /**
     * Call delegate Faker generator methods
     *
     * @param string $method
     * @param array $attributes
     *
     * @return false|mixed
     */
    public function __call($method, $attributes)
    {
        return $this->generator()->$method(...$attributes);
    }

    /**
     * Get delegate Faker generator attribute
     *
     * @param string $attribute
     *
     * @return false|mixed
     */
    public function __get($attribute)
    {
        return $this->generator()->$attribute;
    }

    /**
     * Set delegate Faker generator attribute.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return void
     */
    public function __set(string $attribute, $value): void {}

    /**
     * Check if delegate Faker generator attribute exists.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function __isset(string $attribute): bool
    {
        return isset($this->generator()->$attribute);
    }

    /**
     * @inheritDoc
     */
    public function generator(): FakerGenerator
    {
        if ($this->generator === null) {
            $this->generator = FakerFactory::create($this->locale);
        }

        return $this->generator;
    }

    /**
     * @inheritDoc
     */
    public function setLocale(string $locale): FakerInterface
    {
        $this->locale = $locale;
        $this->generator = null;

        return $this;
    }
}
