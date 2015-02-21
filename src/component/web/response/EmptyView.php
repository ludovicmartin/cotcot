<?php

namespace cotcot\component\web\response;

/**
 * Empty view.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class EmptyView extends Response {

    public function prepare() {
        $this->headers['Content-Length'] = 0;
    }

    public function sendContent() {
        //Nothing to do, view is empty
    }

}
