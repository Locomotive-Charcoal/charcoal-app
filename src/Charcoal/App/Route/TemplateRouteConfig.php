<?php

namespace Charcoal\App\Route;

use \InvalidArgumentException;

// Local namespace dependencies
use \Charcoal\App\Route\RouteConfig;

/**
 *
 */
class TemplateRouteConfig extends RouteConfig
{
    /**
     * The template ident (to load).
     * @var string $template
     */
    private $template;

    /**
     * The view engine ident to use.
     * Ex: "mustache", ""
     * @var string $engine
     */
    private $engine;

    /**
     * Additional template data.
     * @var array $template_data
     */
    private $template_data = [];

    /**
     * Redirect URL.
     * @var string $redirect
     */
    private $redirect;

    /**
     * Redirect Mode (HTTP status code).
     * @var integer $redirect_mode
     */
    private $redirect_mode = 301;

    /**
     * Enable route-level caching for this template.
     * @var boolean $cache
     */
    private $cache = false;

    /**
     * If using cache, the time-to-live, in seconds, of the cache. (0 = no limit).
     * @var integer $cache_ttl
     */
    private $cache_ttl = 0;

    /**
     * @param string|null $template The template identifier.
     * @throws InvalidArgumentException If the tempalte parameter is not null or not a string.
     * @return TemplateRouteConfig Chainable
     */
    public function set_template($template)
    {
        if ($template === null) {
            $this->template = null;
            return $this;
        }
        if (!is_string($template)) {
            throw new InvalidArgumentException(
                'Template must be a string (the template ident)'
            );
        }
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function template()
    {
        if ($this->template === null) {
            return $this->ident();
        }
        return $this->template;
    }

    /**
     * @param string|null $engine The engine identifier (mustache, php, or mustache-php).
     * @throws InvalidArgumentException If the engine is not null or not a string.
     * @return TemplateRouteConfig Chainable
     */
    public function set_engine($engine)
    {
        if ($engine === null) {
            $this->engine = null;
            return $this;
        }
        if (!is_string($engine)) {
            throw new InvalidArgumentException(
                'Engine must be a string (the engine ident)'
            );
        }
        $this->engine = $engine;
        return $this;
    }

    /**
     * @return string
     */
    public function engine()
    {
        if ($this->engine === null) {
            return $this->default_engine();
        }
        return $this->engine;
    }

    /**
     * @return string
     */
    public function default_engine()
    {
        // Must load from default config...
        return 'mustache';
    }

    /**
     * Set the template custom data.
     *
     * @param array $template_data The route template data.
     * @return TemplateRouteConfig Chainable
     */
    public function set_template_data(array $template_data)
    {
        $this->template_data = $template_data;
        return $this;
    }

    /**
     * Get the template custom data.
     *
     * @return array
     */
    public function template_data()
    {
        return $this->template_data;
    }

    /**
     * @param string $redirect Points to a route.
     * @return TemplateRouteConfig Chainable
     */
    public function set_redirect($redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * @return string redirect route
     */
    public function redirect()
    {
        return $this->redirect;
    }

    /**
     * Set the redirect HTTP status mode. (Must be 3xx)
     *
     * @param mixed $redirect_mode The HTTP status code.
     * @throws InvalidArgumentException If the redirect mode is not 3xx.
     * @return TemplateRouteConfig Chainable
     */
    public function set_redirect_mode($redirect_mode)
    {
        $redirect_mode = (int)$redirect_mode;
        if ($redirect_mode < 300 || $redirect_mode  >= 400) {
            throw new InvalidArgumentException(
                'Invalid HTTP status for redirect mode'
            );
        }

        $this->redirect_mode = $redirect_mode;
        return $this;
    }

    /**
     * @return integer
     */
    public function redirect_mode()
    {
        return $this->redirect_mode;
    }

    /**
     * @param boolean $cache The cache enabled flag.
     * @return TemplateRouteConfig Chainable
     */
    public function set_cache($cache)
    {
        $this->cache = !!$cache;
        return $this;
    }

    /**
     * @return boolean
     */
    public function cache()
    {
        return $this->cache;
    }

    /**
     * @param integer $ttl The cache Time-To-Live, in seconds.
     * @return TemplateRouteConfig Chainable
     */
    public function set_cache_ttl($ttl)
    {
        $this->cache_ttl = (int)$ttl;
        return $this;
    }

    /**
     * @return integer
     */
    public function cache_ttl()
    {
        return $this->cache_ttl;
    }
}
