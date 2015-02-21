<?php

namespace cotcot\component\web\application;

/**
 * Application.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Application extends \cotcot\component\application\Application {

    /** @var \cotcot\component\web\router\Router */
    public $router;

    /** @var \cotcot\component\web\request\Request */
    public $request;

    /** @var \cotcot\component\logger\Logger */
    public $logger;

    /** @var array configuration to build a view that render error views */
    public $errorViewConfiguration;

    /**
     * Called before action.
     * @param string $controllerName controller name
     * @param string $actionName action name
     * @return \cotcot\component\web\response\Response|null response (controller action will not be executed if not-null response is returned)
     */
    abstract public function beforeAction($controllerName, $actionName);

    /**
     * Called after action.
     * @param string $controllerName controller name
     * @param string $actionName action name
     * @param \cotcot\component\web\response\Response $actionName controller response
     * @return \cotcot\component\web\response\Response|null response (non-null value will override controller's response)
     */
    abstract public function afterAction($controllerName, $actionName, $response);
}
