<?php

namespace Charcoal\App;

use \Charcoal\App\AppInterface;

/**
 * Interface for objects that depend on an app.
 *
 * Mostly exists to avoid boilerplate code duplication.
 */
interface AppAwareInterface
{
    /**
     * @param AppInterface $app The app instance this object depends on.
     * @return AppAwareInterface Chainable
     */
    public function set_app(AppInterface $app);

    /**
     * @return AppInterface
     */
    public function app();
}
