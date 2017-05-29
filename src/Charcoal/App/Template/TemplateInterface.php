<?php

namespace Charcoal\App\Template;

// From PSR-7
use Psr\Http\Message\RequestInterface;

// From Pimple
use Pimple\Container;

/**
 *
 */
interface TemplateInterface
{

    /**
     * Give an opportunity to children classes to inject dependencies from a Pimple Container.
     *
     * Does nothing by default, reimplement in children classes.
     *
     * The `$container` DI-container (from `Pimple`) should not be saved or passed around, only to be used to
     * inject dependencies (typically via setters).
     *
     * @param Container $container A dependencies container instance.
     * @return void
     */
    public function setDependencies(Container $container);

    /**
     * @param array $data The template data to set.
     * @return TemplateInterface Chainable
     */
    public function setData(array $data);

    /**
     * Initialize the template with a request.
     *
     * @param RequestInterface $request The request to intialize.
     * @return boolean
     */
    public function init(RequestInterface $request);
}
