<?php

namespace cotcot\component\web\response;

/**
 * Error response.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class ErrorView extends PhpView {

    /** @var \Exception exception that caused error */
    public $exception;

}
