<?php

namespace cotcot\component\application;

/**
 * Application.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Application {

    /** @var \cotcot\component\logger\Logger */
    public $logger;

    /**
     * Run the application.
     * @return int|null error code or null if none
     */
    public abstract function run();
}
