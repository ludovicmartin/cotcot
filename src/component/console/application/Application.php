<?php

namespace cotcot\component\console\application;

/**
 * Console application.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Application extends \cotcot\component\application\Application {

    /** @var \cotcot\component\console\commandLine\CommandLine */
    public $commandLine;

    /**
     * Get command usage.
     * @return string
     */
    public abstract function getHelp();

    /**
     * Called before action.
     * @param string $actionName action name
     * @return void
     */
    abstract public function beforeAction($actionName);

    /**
     * Called after action.
     * @param string $actionName action name
     * @return void
     */
    abstract public function afterAction($actionName);
}
