<?php

namespace Charcoal\App;

use \InvalidArgumentException;

// PSR-3 (logger) dependencies
use \Psr\Log\LoggerAwareInterface;
use \Psr\Log\LoggerAwareTrait;

// Local namespace dependencies
use \Charcoal\App\AppAwareInterface;
use \Charcoal\App\AppAwareTrait;
use \Charcoal\App\AppInterface;

/**
 * Managers handle various instances of App-related objects.
 *
 * Examples of managers are `LanguageManager`, `MiddlewareManager`, `ModuleManager` and `RouteManager`.
 */
abstract class AbstractManager implements
    AppAwareInterface,
    LoggerAwareInterface
{
    use AppAwareTrait;
    use LoggerAwareTrait;

    /**
     * @var array
     */
    private $config = [];

    /**
     * Manager constructor
     *
     * @param array $data The dependencies container.
     */
    final public function __construct(array $data)
    {
        if (isset($data['logger'])) {
            $this->setLogger($data['logger']);
        }

        $this->set_config($data['config']);
        $this->set_app($data['app']);
    }

    /**
     * Set the manager's config
     *
     * @param  ConfigInterface|array $config The manager configuration.
     * @return self
     */
    public function set_config($config = [])
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Get the manager's config
     *
     * @return array
     */
    public function config()
    {
        return $this->config;
    }
}
