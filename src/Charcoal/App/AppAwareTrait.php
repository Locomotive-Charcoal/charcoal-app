<?php

namespace Charcoal\App;

use \Charcoal\App\AppInterface;

/**
* Implementation, as trait, of the `AppAwareInterface`.
*/
trait AppAwareTrait
{
    /**
     * @var AppInterface $app
     */
    private $app;

    /**
     * @param AppInterface $app The app instance this object depends on.
     * @return AppAwareInterface Chainable
     */
    public function set_app(AppInterface $app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * @return AppInterface
     */
    public function app()
    {
        return $this->app;
    }
}