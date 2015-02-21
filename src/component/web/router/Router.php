<?php

namespace cotcot\component\web\router;

/**
 * Router.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Router {

    /** @var \cotcot\component\web\request\Request request */
    public $request;

    /**
     * Get controller name.
     * @return string controller name
     */
    public abstract function getControllerName();

    /**
     * Get action name.
     * @return string action name
     */
    public abstract function getActionName();

    /**
     * Get params.
     * @return array parameters
     */
    public abstract function getParameters();

    /**
     * Get route.
     * @return string route
     */
    public abstract function getRoute();
}
