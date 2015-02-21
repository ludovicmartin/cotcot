<?php

namespace cotcot\component\console\commandLine;

/**
 * Command line.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class CommandLine {

    /**
     * Get argument value.
     * @param string $index index to get
     * @param mixed $default default value
     * @return array|mixed data
     */
    public abstract function getArgument($index = null, $default = null);

    /**
     * Get command name.
     * @return string
     */
    public abstract function getCommandName();
}
