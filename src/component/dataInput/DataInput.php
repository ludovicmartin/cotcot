<?php

namespace cotcot\component\dataInput;

/**
 * Data input manager.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class DataInput implements \cotcot\core\Initializable {

    /** @var \cotcot\component\filter\Filter filters */
    public $filters = array();

    /** @var \cotcot\component\validator\Validator validators */
    public $validators = array();

    /** @var array data */
    private $data = array();

    /** @var array messages */
    private $messages = array();

    public function init() {
        $this->data = array_fill_keys($this->buildFieldList(), null);
    }

    /**
     * Build field list.
     * @return array field list
     */
    private function buildFieldList() {
        $fields = array();
        foreach (array_keys($this->validators) as $field) {
            $fields[] = $field;
        }
        foreach (array_keys($this->filters) as $field) {
            $fields[] = $field;
        }
        return array_unique($fields);
    }

    /**
     * Hydrate form.
     * @param array $data input data
     */
    public function setData($data) {
        $this->data = array();
        foreach ($this->buildFieldList() as $fieldName) {
            $value = isset($data[$fieldName]) ? $data[$fieldName] : null;
            if (isset($this->filters[$fieldName])) {
                foreach ($this->filters[$fieldName] as $filter) {
                    $value = $filter->filter($value);
                }
            }
            $this->data[$fieldName] = $value;
        }
    }

    /**
     * Get filtered data from form.
     * @param string $name param name
     * @return mixed data
     */
    public function getData($name = null) {
        if ($name !== null) {
            return isset($this->data[$name]) ? $this->data[$name] : null;
        }
        return $this->data;
    }

    /**
     * Validate form data.
     * @return boolean true if valid
     */
    public function isValid() {
        $fields = $this->buildFieldList();
        $this->messages = array_fill_keys($fields, array());
        $result = true;
        foreach ($fields as $fieldName) {
            if (isset($this->validators[$fieldName])) {
                $validatorContext = array(
                    'fieldName' => $fieldName,
                    'values' => $this->data
                );
                foreach ($this->validators[$fieldName] as $validator) {
                    $brokenChain = false;
                    try {
                        $validatorResult = $validator->isValid(isset($this->data[$fieldName]) ? $this->data[$fieldName] : null, $validatorContext);
                    } catch (\cotcot\component\exception\BreakChainException $ex) {
                        $validatorResult = $ex->status;
                        $brokenChain = true;
                    }
                    $result = $result && $validatorResult;
                    $messages = array();
                    foreach ($validator->getMessages() as $message) {
                        $messages[] = $message;
                    }
                    $this->messages[$fieldName] = $messages;
                    if (!$validatorResult || $brokenChain) {
                        break;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Get messages resulting of validation.
     * @param string $name param name
     * @return array messages
     */
    public function getMessages($name = null) {
        if ($name !== null) {
            return isset($this->messages[$name]) ? $this->messages[$name] : array();
        }
        return $this->messages;
    }

    /**
     * Test if has message.
     * @param string $name param name
     * @return boolean
     */
    public function hasMessage($name = null) {
        if ($name !== null) {
            return isset($this->messages[$name]) && count($this->messages[$name]) > 0;
        }
        foreach ($this->messages as $messages) {
            if (count($messages) > 0) {
                return true;
            }
        }
        return false;
    }

}
