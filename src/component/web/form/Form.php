<?php

namespace cotcot\component\web\form;

class Form extends \cotcot\component\dataInput\DataInput {

    /** @var string name */
    public $name;

    /** @var string name */
    public $method = 'post';

    /** @var string name */
    public $action;

    /** @var string name */
    public $enctype = 'application/x-www-form-urlencoded';

}
