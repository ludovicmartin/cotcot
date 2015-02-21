<?php

namespace cotcot\component\validator;

/**
 * Validator directory.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Directory extends \cotcot\tools\Directory {

    public function init() {
        $this->defaultItems = array(
            'and' => array('classname' => '\cotcot\component\validator\AndValidator', 'singleton' => false),
            'count' => array('classname' => '\cotcot\component\validator\ArrayCount', 'singleton' => false),
            'callback' => array('classname' => '\cotcot\component\validator\Callback', 'singleton' => false),
            'csrf' => array('classname' => '\cotcot\component\validator\Csrf', 'singleton' => false),
            'date' => array('classname' => '\cotcot\component\validator\DateString', 'singleton' => false),
            'equal' => array('classname' => '\cotcot\component\validator\Equal', 'singleton' => false),
            'notEqual' => array('classname' => '\cotcot\component\validator\Equal', 'singleton' => false, 'attributes' => array('type' => Equal::NOT_EQUAL)),
            'equalTo' => array('classname' => '\cotcot\component\validator\EqualTo', 'singleton' => false),
            'notEqualTo' => array('classname' => '\cotcot\component\validator\EqualTo', 'singleton' => false, 'attributes' => array('type' => Equal::NOT_EQUAL)),
            'in' => array('classname' => '\cotcot\component\validator\InArray', 'singleton' => false),
            'array' => array('classname' => '\cotcot\component\validator\IsArray', 'singleton' => false),
            'email' => array('classname' => '\cotcot\component\validator\IsEmail', 'singleton' => false),
            'float' => array('classname' => '\cotcot\component\validator\IsFloat', 'singleton' => false),
            'int' => array('classname' => '\cotcot\component\validator\IsInt', 'singleton' => false),
            'null' => array('classname' => '\cotcot\component\validator\IsNull', 'singleton' => false),
            'notNull' => array('classname' => '\cotcot\component\validator\IsNull', 'singleton' => false, 'attributes' => array('type' => IsNull::IS_NOT_NULL)),
            'scalar' => array('classname' => '\cotcot\component\validator\IsScalar', 'singleton' => false),
            'string' => array('classname' => '\cotcot\component\validator\IsString', 'singleton' => false),
            'url' => array('classname' => '\cotcot\component\validator\IsUrl', 'singleton' => false),
            'range' => array('classname' => '\cotcot\component\validator\Range', 'singleton' => false),
            'or' => array('classname' => '\cotcot\component\validator\OrValidator', 'singleton' => false),
            'length' => array('classname' => '\cotcot\component\validator\StringLength', 'singleton' => false),
            'match' => array('classname' => '\cotcot\component\validator\StringPregMatch', 'singleton' => false),
            'required' => array('classname' => '\cotcot\component\validator\Required', 'singleton' => false, 'attributes' => array('type' => Required::CONTENT_REQUIRED)),
            'notRequired' => array('classname' => '\cotcot\component\validator\Required', 'singleton' => false, 'attributes' => array('type' => Required::CONTENT_NOT_REQUIRED)),
            'safe' => array('classname' => '\cotcot\component\validator\Safe', 'singleton' => false),
            'isNumeric' => array('classname' => '\cotcot\component\validator\IsNumeric', 'singleton' => false),
            'each' => array('classname' => '\cotcot\component\validator\IteratorWalk', 'singleton' => false),
            'isUploadedFile' => array('classname' => '\cotcot\component\validator\IsUploadedFile', 'singleton' => false),
            'fileSize' => array('classname' => '\cotcot\component\validator\FileSize', 'singleton' => false),
            'fileCallback' => array('classname' => '\cotcot\component\validator\FileCallback', 'singleton' => false),
            'fileRequired' => array('classname' => '\cotcot\component\validator\FileRequired', 'singleton' => false, 'attributes' => array('type' => FileRequired::FILE_REQUIRED)),
            'fileNotRequired' => array('classname' => '\cotcot\component\validator\FileRequired', 'singleton' => false, 'attributes' => array('type' => FileRequired::FILE_NOT_REQUIRED))
        );
        parent::init();
    }

    /**
     * Get a validator.
     * @param string $name validator name
     * @param string $attributes custom attributes merged with configuration
     * @return Validator validator
     */
    public function getValidator($name, $attributes = null) {
        return $this->getItem($name, $attributes);
    }

}
