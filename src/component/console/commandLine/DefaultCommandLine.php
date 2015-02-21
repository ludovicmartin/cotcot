<?php

namespace cotcot\component\console\commandLine;

/**
 * Default command line.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class DefaultCommandLine extends CommandLine {

    public function getArgument($index = null, $default = null) {
        return $index === null ? $_SERVER['argv'] : (isset($_SERVER['argv'][$index]) ? $_SERVER['argv'][$index] : $default);
    }

    public function getCommandName() {
        return $_SERVER['SCRIPT_NAME'];
    }

}
