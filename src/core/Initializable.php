<?php

namespace cotcot\core;

/**
 * Initializable interface.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
interface Initializable {

    /**
     * Initialization processing method.
     * @return void
     */
    public function init();
}
