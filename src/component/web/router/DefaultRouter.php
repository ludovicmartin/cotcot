<?php

namespace cotcot\component\web\router;

/**
 * Default router.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class DefaultRouter extends Router {

    /** boolean */
    private $parsed = false;

    /** string */
    private $route = null;

    /** @var string */
    private $controllerName = null;

    /** @var string */
    private $actionName = null;

    /** @var string */
    private $parameters = null;

    /** @var array */
    public $rules = array();

    public function getControllerName() {
        if (!$this->parsed) {
            $this->parseRoute();
        }
        return $this->controllerName;
    }

    public function getActionName() {
        if (!$this->parsed) {
            $this->parseRoute();
        }
        return $this->actionName;
    }

    public function getParameters() {
        if (!$this->parsed) {
            $this->parseRoute();
        }
        return $this->parameters;
    }

    /**
     * Parse route.
     * @return void
     */
    private function parseRoute() {
        $routeItems = explode('/', $this->getRoute());
        foreach ($this->rules as $rule => $ruleParams) {
            if (count($ruleParams) < 2 || !isset($ruleParams[0]) || !isset($ruleParams[1])) {
                throw new \Exception('route configuration error: ' . $rule);
            }
            $parameters = array();
            $ruleItems = explode('/', $rule);
            $ruleItemCount = count($ruleItems);
            if ($ruleItemCount === count($routeItems)) {
                $matches = true;
                for ($i = 0; $i < $ruleItemCount; $i++) {
                    if ($ruleItems[$i] !== $routeItems[$i]) {
                        if (strlen($ruleItems[$i]) && $ruleItems[$i][0] === ':') {
                            $parameters[substr($ruleItems[$i], 1)] = $routeItems[$i];
                        } else {
                            $matches = false;
                            break;
                        }
                    }
                }
                if ($matches) {
                    $parameters = array_merge($this->request->getGet(), $parameters);
                    if (isset($ruleParams[2])) {
                        $parameters = array_merge($parameters, $ruleParams[2]);
                    }
                    $this->parameters = $parameters;
                    $this->controllerName = $ruleParams[0];
                    $this->actionName = $ruleParams[1];
                    break;
                }
            }
        }
        $this->parsed = true;
    }

    public function getRoute() {
        if ($this->route === null) {
            $uri = $this->request->getServer('REQUEST_URI');
            $questionMarkIndex = strrpos($uri, '?');
            if ($questionMarkIndex !== false) {
                $uri = substr($uri, 0, $questionMarkIndex);
            }
            $this->route = $uri != '/' ? rtrim($uri, '/') : $uri;
        }
        return $this->route;
    }

}
