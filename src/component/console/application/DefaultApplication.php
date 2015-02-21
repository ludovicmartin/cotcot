<?php

namespace cotcot\component\console\application;

/**
 * Default console application.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class DefaultApplication extends Application {

    public function run() {
        $args = $this->commandLine->getArgument();
        if (count($args) < 2) {
            $this->printError('no action name given');
            return 1;
        }
        $actionName = $args[1] . 'Action';
        if (method_exists($this, $actionName)) {
            $result = 1;
            try {
                $this->beforeAction($actionName);
                $result = call_user_func(array($this, $actionName), array_slice($args, 2));
                $this->afterAction($actionName);
            } catch (\Exception $ex) {
                $this->printError($ex->getMessage());
            }
            return $result;
        }
        $this->printError('action "' . $actionName . '" not found');
        return 1;
    }

    /**
     * Help action.
     * @param array $params params
     * @return int error code
     */
    public function helpAction($params = array()) {
        $this->printHelp();
        return 0;
    }

    public function getHelp() {
        return $this->commandLine->getCommandName();
    }

    /**
     * Print command help.
     * @param array $params params
     * @return void
     */
    public function printHelp() {
        print('Usage: ' . $this->getHelp() . PHP_EOL);
    }

    /**
     * Print a usage error.
     * This method will then terminate the execution of the current application.
     * @param string $message the error message
     */
    public function printError($message) {
        fwrite(STDERR, 'Error: ' . $message . PHP_EOL);
    }

    public function beforeAction($actionName) {
        $this->logger->log(get_class($this) . '::' . $actionName, \cotcot\component\logger\Logger::LEVEL_DEBUG, 'BEFORE-ACTION');
    }

    public function afterAction($actionName) {
        $this->logger->log(get_class($this) . '::' . $actionName, \cotcot\component\logger\Logger::LEVEL_DEBUG, 'AFTER-ACTION');
    }

}
