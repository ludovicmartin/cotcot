<?php

namespace cotcot\component\web\controller;

/**
 * Abstract controller.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Controller implements \cotcot\core\RuntimeContextAware {

    /** @var \cotcot\core\RuntimeContext */
    public $runtimeContext;

    /** @var \cotcot\component\web\request\Request */
    public $request;

    /** @var array response configuration */
    public $responseConfiguration;

    /**
     * Called before action.
     * @param string $actionName action name
     * @return \cotcot\component\web\response\Response|null response (controller action will not be executed if not-null response is returned)
     */
    public function beforeAction($actionName) {
        return null;
    }

    /**
     * Called after action.
     * @param string $actionName action name
     * @param \cotcot\component\web\response\Response $actionName controller response
     * @return \cotcot\component\web\response\Response|null response (non-null value will override controller's response)
     */
    public function afterAction($actionName, $response) {
        return null;
    }

    /**
     * Create a response.
     * @param array $attributes response attributes
     * @return \cotcot\component\web\response\Response response
     */
    public function createResponse($attributes) {
        return $this->runtimeContext->createObject($this->responseConfiguration, $attributes);
    }

}
