<?php

namespace Charcoal\App\ServiceProvider;

// Dependencies from Pimple
use \Pimple\ServiceProviderInterface;
use \Pimple\Container;

// Dependencies from charcoal-factory
use \Charcoal\Factory\GenericFactory as Factory;

// Intra-module (`charcoal-app`) dependencies
use \Charcoal\App\Action\ActionInterface;
use \Charcoal\App\Script\ScriptInterface;

use \Charcoal\App\Handler\Error;
use \Charcoal\App\Handler\PhpError;
use \Charcoal\App\Handler\Shutdown;
use \Charcoal\App\Handler\NotAllowed;
use \Charcoal\App\Handler\NotFound;

use \Charcoal\App\Template\TemplateInterface;
use \Charcoal\App\Template\TemplateBuilder;
use \Charcoal\App\Template\WidgetInterface;
use \Charcoal\App\Template\WidgetBuilder;

/**
 * Application Service Provider
 *
 * Configures Charcoal and Slim and provides various Charcoal services to a container.
 *
 * ## Services
 * - `logger` `\Psr\Log\Logger`
 *
 * ## Helpers
 * - `logger/config` `\Charcoal\App\Config\LoggerConfig`
 *
 * ## Requirements / Dependencies
 * - `config` A `ConfigInterface` must have been previously registered on the container.
 */
class AppServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $container A container instance.
     * @return void
     */
    public function register(Container $container)
    {
        $this->registerHandlerServices($container);
        $this->registerRouteServices($container);
        $this->registerRequestControllerServices($container);
        $this->registerModuleServices($container);
    }

    /**
     * @param Container $container The DI container.
     * @return void
     */
    protected function registerHandlerServices(Container $container)
    {
        $config = $container['config'];

        if (!isset($config['handlers'])) {
            return;
        }

        /**
         * HTTP 404 (Not Found) handler.
         *
         * @param  object|HandlerInterface $handler   An error handler instance.
         * @param  Container               $container A container instance.
         * @return HandlerInterface
         */
        $container->extend('notFoundHandler', function ($handler, Container $container) use ($config) {
            if ($handler instanceof \Slim\Handlers\NotFound) {
                $handler = new NotFound($container);

                if (isset($config['handlers']['notFound'])) {
                    $handler->config()->merge($config['handlers']['notFound']);
                }

                $handler->init();
            }

            return $handler;
        });

        /**
         * HTTP 405 (Not Allowed) handler.
         *
         * @param  object|HandlerInterface $handler   An error handler instance.
         * @param  Container               $container A container instance.
         * @return HandlerInterface
         */
        $container->extend('notAllowedHandler', function ($handler, Container $container) use ($config) {
            if ($handler instanceof \Slim\Handlers\NotAllowed) {
                $handler = new NotAllowed($container);

                if (isset($config['handlers']['notAllowed'])) {
                    $handler->config()->merge($config['handlers']['notAllowed']);
                }

                $handler->init();
            }

            return $handler;
        });

        /**
         * HTTP 500 (Error) handler for PHP 7+ Throwables.
         *
         * @param  object|HandlerInterface $handler   An error handler instance.
         * @param  Container               $container A container instance.
         * @return HandlerInterface
         */
        $container->extend('phpErrorHandler', function ($handler, Container $container) use ($config) {
            if ($handler instanceof \Slim\Handlers\PhpError) {
                $handler = new PhpError($container);

                if (isset($config['handlers']['phpError'])) {
                    $handler->config()->merge($config['handlers']['phpError']);
                }

                $handler->init();
            }

            return $handler;
        });

        /**
         * HTTP 500 (Error) handler.
         *
         * @param  object|HandlerInterface $handler   An error handler instance.
         * @param  Container               $container A container instance.
         * @return HandlerInterface
         */
        $container->extend('errorHandler', function ($handler, Container $container) use ($config) {
            if ($handler instanceof \Slim\Handlers\Error) {
                $handler = new Error($container);

                if (isset($config['handlers']['error'])) {
                    $handler->config()->merge($config['handlers']['error']);
                }

                $handler->init();
            }

            return $handler;
        });

        if (!isset($container['shutdownHandler'])) {
            /**
             * HTTP 503 (Service Unavailable) handler.
             *
             * This handler is not part of Slim.
             *
             * @param  Container $container A container instance.
             * @return HandlerInterface
             */
            $container['shutdownHandler'] = function (Container $container) {
                $config  = $container['config'];
                $handler = new Shutdown($container);

                if (isset($config['handlers']['shutdown'])) {
                    $handler->config()->merge($config['handlers']['shutdown']);
                }

                return $handler->init();
            };
        }
    }

    /**
     * @param Container $container The DI container.
     * @return void
     */
    protected function registerRouteServices(Container $container)
    {
        /**
         * @param Container $container A container instance.
         * @return \Charcoal\Factory\FactoryInterface
         */
        $container['route/factory'] = function (Container $container) {
            return new Factory([
                'base_class' => '\Charcoal\App\Route\RouteInterface',
                'resolver_options' => [
                    'suffix' => 'Route'
                ],
                'arguments' => [[
                    'logger' => $container['logger']
                ]]
            ]);
        };
    }

    /**
     * @param Container $container The DI container.
     * @return void
     */
    protected function registerRequestControllerServices(Container $container)
    {
        /**
         * @param Container $container A container instance.
         * @return \Charcoal\Factory\FactoryInterface
         */
        $container['action/factory'] = function (Container $container) {
            return new Factory([
                'base_class' => '\Charcoal\App\Action\ActionInterface',
                'resolver_options' => [
                    'suffix' => 'Action'
                ],
                'arguments' => [[
                    'logger' => $container['logger']
                ]],
                'callback' => function(ActionInterface $obj) use ($container) {
                    $obj->setDependencies($container);
                }
            ]);
        };

        /**
         * @param Container $container A container instance.
         * @return \Charcoal\Factory\FactoryInterface
         */
        $container['script/factory'] = function (Container $container) {
            return new Factory([
                'base_class' => '\Charcoal\App\Script\ScriptInterface',
                'resolver_options' => [
                    'suffix' => 'Script'
                ],
                'arguments' => [[
                    'logger' => $container['logger']
                ]],
                'callback' => function(ScriptInterface $obj) use ($container) {
                    $obj->setDependencies($container);
                }
            ]);
        };

        /**
         * @param Container $container A container instance.
         * @return \Charcoal\Factory\FactoryInterface
         */
        $container['template/factory'] = function (Container $container) {
            return new Factory([
                'base_class' => '\Charcoal\App\Template\TemplateInterface',
                'resolver_options' => [
                    'suffix' => 'Template'
                ],
                'arguments' => [[
                    'logger' => $container['logger']
                ]],
                'callback' => function(TemplateInterface $obj) use ($container) {
                    $obj->setDependencies($container);
                }
            ]);
        };

        /**
         * @param Container $container A container instance.
         * @return \Charcoal\Factory\FactoryInterface
         */
        $container['widget/factory'] = function (Container $container) {
            return new Factory([
                'base_class' => '\Charcoal\App\Template\WidgetInterface',
                'resolver_options' => [
                    'suffix' => 'Widget'
                ],
                'arguments' => [[
                    'logger' => $container['logger']
                ]],
                'callback' => function(WidgetInterface $obj) use ($container) {
                    $obj->setDependencies($container);
                }
            ]);
        };
        /**
         * @param Container $container A container instance.
         * @return TemplateBuilder
         */
        $container['widget/builder'] = function (Container $container) {
            return new WidgetBuilder($container['widget/factory'], $container);
        };
    }

    /**
     * @param Container $container The DI container.
     * @return void
     */
    protected function registerModuleServices(Container $container)
    {
        /**
         * @param Container $container A container instance.
         * @return \Charcoal\Factory\FactoryInterface
         */
        $container['module/factory'] = function (Container $container) {
            return new Factory([
                'base_class' => '\Charcoal\App\Module\ModuleInterface',
                'resolver_options' => [
                    'suffix' => 'Module'
                ],
                'arguments' => [[
                    'logger' => $container['logger']
                ]]
            ]);
        };
    }
}