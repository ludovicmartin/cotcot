<?php

namespace cotcot\component\locale;

/**
 * Locale detector (using HTTP request param).
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class RequestParamLocaleDetector extends LocaleDetector {

    const TYPE_GET = 1;
    const TYPE_POST = 2;
    const TYPE_COOKIE = 3;

    /** @var \cotcot\component\web\request\Request */
    public $request;

    /** @var string name of the param that determine language */
    public $paramName = 'language';

    /** @var int type of param to detect language (GET, POST, COOKIE) */
    public $paramType = self::TYPE_GET;

    public function getLocale() {
        $value = null;
        switch ($this->paramType) {
            case self::TYPE_GET:
                $value = $this->request->getGet($this->paramName);
                break;
            case self::TYPE_POST:
                $value = $this->request->getPost($this->paramName);
                break;
            case self::TYPE_COOKIE:
                $value = $this->request->getCookie($this->paramName);
                break;
        }
        return \cotcot\component\locale\Locale::parse($value);
    }

}
