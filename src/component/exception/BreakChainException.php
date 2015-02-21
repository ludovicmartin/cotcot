<?php

namespace cotcot\component\exception;

/**
 * Break validation chain exception.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class BreakChainException extends \Exception {

    /** @var boolean validation status when chain is broken */
    public $status;

    /**
     * Construct a BreakChainException object.
     * @param boolean $status validation status
     */
    public function __construct($status) {
        parent::__construct(null, null, null);
        $this->status = $status;
    }

}
