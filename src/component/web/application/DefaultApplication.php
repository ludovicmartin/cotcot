<?php

namespace cotcot\component\web\application;

/**
 * Default web application.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class DefaultApplication extends Application implements \cotcot\core\RuntimeContextAware, \cotcot\core\Initializable {

    /** @var \cotcot\core\RuntimeContext */
    public $runtimeContext;

    /** @var int application start timestamp */
    private $startTime;

    public function init() {
        $this->startTime = microtime(true) * 1000;
    }

    public function run() {
        $response = null;
        $controllerName = $this->router->getControllerName();
        $actionName = $this->router->getActionName();
        $actionParams = $this->router->getParameters();
        try {
            $response = $this->beforeAction($controllerName, $actionName);
            if ($response === null && $controllerName !== null && $actionName !== null && $actionParams !== null) {
                //Get controller
                $controller = $this->runtimeContext->getComponent($controllerName);
                if ($controller !== null && $controller instanceof \cotcot\component\web\controller\Controller) {
                    //Get action
                    if (method_exists($controller, $actionName)) {
                        //Run action and get response
                        $response = $controller->beforeAction($actionName);
                        if ($response === null) {
                            $response = call_user_func(array($controller, $actionName), $actionParams);
                        }
                        $afterControllerActionResponse = $controller->afterAction($actionName, $response);
                        if ($afterControllerActionResponse !== null) {
                            $response = $afterControllerActionResponse;
                        }
                    }
                }
            }
            $afterActionResponse = $this->afterAction($controllerName, $actionName, $response);
            if ($afterActionResponse !== null) {
                $response = $afterActionResponse;
            }
            if ($response === null) {
                throw new \cotcot\component\exception\HttpException(null, 404);
            }
            if (!$response instanceof \cotcot\component\web\response\Response) {
                throw new \Exception('bad reponse class: ' . get_class($response));
            }
        } catch (\Exception $ex) {
            $response = $this->buildResponseForException($ex);
        }
        //Send response
        try {
            $this->sendResponse($response);
        } catch (\Exception $ex) {
            //Fallback to internal error 500 page
            $this->logger->log($this->request->getServer('REQUEST_URI') . ': ' . $ex->getMessage(), \cotcot\component\logger\Logger::LEVEL_ERROR, 'CONTENT-SEND');
            $this->sendInternalError500($ex);
        }
        $this->logProfileMessage('PROFILE-AFTER_SEND');
    }

    /**
     * Build a response for a given exception.
     * @param \Exception $exception exception
     */
    private function buildResponseForException($exception) {
        $code = $exception->getCode();
        if ($code === null || $code < 400 || !$exception instanceof \cotcot\component\exception\HttpException) {
            $code = 500;
        }
        $message = $exception->getMessage();
        if (empty($message)) {
            $message = 'error ' . $code;
        }
        $this->logger->log($this->request->getServer('REQUEST_URI') . ': ' . $message, \cotcot\component\logger\Logger::LEVEL_ERROR, 'HTTP-' . $code);
        return $this->runtimeContext->createObject($this->errorViewConfiguration, array(
                    'responseCode' => $code,
                    'exception' => $exception
        ));
    }

    /**
     * Send a response to client.
     * @param \cotcot\component\web\response\Response $response
     * @return void
     */
    private function sendResponse($response) {
        //Prepare response
        $response->prepare();
        //Client cache managment
        $sendResponseContent = true;
        $cacheHeaders = array();
        if ($response->clientCacheData !== null) {
            $cacheHeaders = $response->clientCacheData->getHeaders();
            $ifModifiedSinceString = $this->request->getServer('HTTP_IF_MODIFIED_SINCE', '');
            $ifModifiedSince = strlen($ifModifiedSinceString) > 0 ? strtotime(preg_replace('/^(.*)(Mon|Tue|Wed|Thu|Fri|Sat|Sun)(.*)(GMT)(.*)/', '$2$3 GMT', $ifModifiedSinceString)) : null;
            if ($ifModifiedSince !== null && $response->clientCacheData->lastModified !== null && $ifModifiedSince == $response->clientCacheData->lastModified) {
                $response->responseCode = 304;
                $cacheHeaders['Content-Length'] = '0';
                $sendResponseContent = false;
            }
        } else {
            $cacheHeaders['Expires'] = 'Sat, 01 Jan 1970 00:00:00 GMT';
            $cacheHeaders['Last-Modified'] = gmdate('D, d M Y H:i:s') . ' GMT';
            $cacheHeaders['Pragma'] = 'no-cache';
            $cacheHeaders['Cache-Control'] = 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0';
        }
        $response->headers = array_merge($response->headers, $cacheHeaders);
        //Send response
        $response->sendHeaders();
        if ($sendResponseContent) {
            $response->sendContent();
        }
    }

    /**
     * Log profile message.
     * @param string $category category
     */
    private function logProfileMessage($category) {
        $this->logger->log($this->request->getServer('REQUEST_URI') . ': ' . round((microtime(true) * 1000) - $this->startTime, 3), \cotcot\component\logger\Logger::LEVEL_DEBUG, $category);
    }

    /**
     * Send HTTP error 500.
     * @param \Exception $ex exception
     * @return void
     */
    private function sendInternalError500($ex = null) {
        //Clear headers
        if (!headers_sent()) {
            http_response_code(500);
        }
        //Clear output buffer
        ob_clean();
        //Output custom error page
        print('<html><body><h1>500 Internal Server Error</h1>');
        print( htmlspecialchars($ex->getMessage()));
        print('</body></html>');
    }

    public function beforeAction($controllerName, $actionName) {
        $this->logger->log($this->request->getServer('REQUEST_URI'), \cotcot\component\logger\Logger::LEVEL_INFO, 'HTTP-QUERY');
        $this->logProfileMessage('PROFILE-BEFORE_ACTION');
        return null;
    }

    public function afterAction($controllerName, $actionName, $response) {
        $this->logProfileMessage('PROFILE-AFTER_ACTION');
        return null;
    }

}
