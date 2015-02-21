<?php

namespace cotcot\tools;

/**
 * Html utils.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class HtmlUtils {

    /**
     * Render an input.
     * @param string $name field name
     * @param string $type input tag type
     * @param \cotcot\component\web\form\Form|null $form form
     * @param array $tagAttributes HTML tag attributes
     * @return string HTML code
     */
    public static function renderInput($name, $type = 'text', $form = null, $tagAttributes = array()) {
        $tagAttributes['name'] = ($form !== null && $form->name !== null) ? ($form->name . '[' . $name . ']') : $name;
        $tagAttributes['type'] = $type;
        if ($form !== null && $type !== 'file') {
            $data = $form->getData($name);
            if ($data !== null) {
                $tagAttributes['value'] = $data;
            }
        }
        return '<input ' . self::formatTagAttributes($tagAttributes) . ' />';
    }

    /**
     * Render a select box.
     * @param string $name field name
     * @param \cotcot\component\web\form\Form|null $form form
     * @param array $tagAttributes HTML tag attributes
     * @return string HTML code
     */
    public static function renderSelect($name, $options = array(), $form = null, $tagAttributes = array()) {
        $tagAttributes['name'] = ($form !== null && $form->name !== null) ? ($form->name . '[' . $name . ']') : $name;
        $data = $form !== null ? $form->getData($name) : null;
        if ($data !== null && !is_array($data)) {
            $data = array($data);
        }
        $result = array();
        $result[] = '<select ' . self::formatTagAttributes($tagAttributes) . '>';
        foreach ($options as $key => $value) {
            $optionAttributes = array();
            $optionAttributes['value'] = $key;
            if ($data !== null && in_array($key, $data)) {
                $optionAttributes['selected'] = 'selected';
            }
            $result[] = '<option ' . self::formatTagAttributes($optionAttributes) . '>';
            $result[] = htmlspecialchars($value);
            $result[] = '</option>';
        }
        $result[] = '</select>';
        return implode('', $result);
    }

    /**
     * Render a checkable input.
     * @param string $name field name
     * @param string $type input tag type
     * @param string $value value
     * @param \cotcot\component\web\form\Form|null $form form
     * @param array $tagAttributes HTML tag attributes
     * @return string HTML code
     */
    public static function renderCheckableInput($name, $type = 'checkbox', $value = 1, $form = null, $tagAttributes = array()) {
        $tagAttributes['name'] = ($form !== null && $form->name !== null) ? ($form->name . '[' . $name . ']') : $name;
        $tagAttributes['value'] = $value;
        $tagAttributes['type'] = $type;
        if ($form !== null) {
            $data = $form->getData($name);
            if ($data == $value) {
                $tagAttributes['checked'] = 'checked';
            }
        }
        return '<input ' . self::formatTagAttributes($tagAttributes) . ' />';
    }

    /**
     * Render a checkable input list.
     * @param string $name field name
     * @param string $type input tag type
     * @param array $values values (key=>label)
     * @param \cotcot\component\web\form\Form|null $form form
     * @param array $labelTagAttributes HTML label tag attributes
     * @param array $inputTagAttributes HTML input tag attributes
     * @return string HTML code
     */
    public static function renderCheckableInputList($name, $type = 'checkbox', $values = array(), $form = null, $labelTagAttributes = array(), $inputTagAttributes = array()) {
        $result = array();
        foreach ($values as $value => $label) {
            $itemInputTagAttributes = $inputTagAttributes;
            $itemInputTagAttributes['name'] = (($form !== null && $form->name !== null) ? ($form->name . '[' . $name . ']') : $name) . ($type == 'checkbox' ? '[]' : '');
            $itemInputTagAttributes['value'] = $value;
            $itemInputTagAttributes['type'] = $type;
            if ($form !== null) {
                $data = $form->getData($name);
                if (($type == 'checkbox' && in_array($value, $data)) || ($type == 'radio' && $data == $value)) {
                    $itemInputTagAttributes['checked'] = 'checked';
                }
            }
            $result[] = '<label ' . self::formatTagAttributes($labelTagAttributes) . '>';
            $result[] = '<input ' . self::formatTagAttributes($itemInputTagAttributes) . ' />';
            $result[] = $label;
            $result[] = '</label>';
        }
        return implode('', $result);
    }

    /**
     * Render an image.
     * @param string $src image source
     * @param string $alt alternative text
     * @param array $tagAttributes HTML tag attributes
     * @return string HTML code
     */
    public static function renderImage($src, $alt = '', $tagAttributes = array()) {
        $tagAttributes['src'] = $src;
        $tagAttributes['alt'] = $alt;
        return '<img ' . self::formatTagAttributes($tagAttributes) . ' />';
    }

    /**
     * Format tag attributes.
     * @param array $attributes attributes
     * @return string partial HTML code
     */
    public static function formatTagAttributes($attributes = array()) {
        $result = array();
        foreach ($attributes as $key => $value) {
            $result[] = $key . '="' . htmlspecialchars($value) . '"';
        }
        return implode(' ', $result);
    }

    /**
     * Render a message list.
     * @param array $messages messages
     * @param array $tagAttributes attributes for list HTML tag 
     * @param boolean $escapeHtml use htmlspecialchars escaping for each message string
     * @return string partial HTML code
     */
    public static function renderMessageList($messages, $tagAttributes = array(), $escapeHtml = true) {
        $temp = array();
        if (count($messages)) {
            $temp[] = '<ul ' . self::formatTagAttributes($tagAttributes) . '>';
            foreach ($messages as $message) {
                $temp[] = '<li>' . ($escapeHtml ? htmlspecialchars($message) : $message) . '</li>';
            }
            $temp[] = '</ul>';
        }
        return implode('', $temp);
    }

    /**
     * Render a textarea field.
     * @param string $name field name
     * @param \cotcot\component\web\form\Form|null $form form
     * @param array $tagAttributes HTML tag attributes
     * @return string HTML code
     */
    public static function renderTextarea($name, $form = null, $tagAttributes = array()) {
        $tagAttributes['name'] = ($form !== null && $form->name !== null) ? ($form->name . '[' . $name . ']') : $name;
        $value = '';
        if ($form !== null) {
            $data = $form->getData($name);
            if ($data !== null) {
                $value = $data;
            }
        }
        return '<textarea ' . self::formatTagAttributes($tagAttributes) . '>' . $value . '</textarea>';
    }

    /**
     * Render a tagged string to HTML code.
     * @param string $string input string
     * @param string $callback callback function to manage tags (function($tagName, $tagParams))
     * @param boolean $multiline transforme new lines to "br" HTML tag
     * @return string HTML code
     */
    public static function renderTaggedText($string, $callback, $multiline = true) {
        $output = array();
        $matches = array();
        $contentStartIndex = 0;
        preg_match_all('\'\[(/{0,1}[a-z0-9_-]+)={0,1}([^]]*)\]\'i', $string, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
        for ($i = 0; $i < count($matches); $i++) {
            $tagStartIndex = $matches[$i][0][1];
            $tagLength = strlen($matches[$i][0][0]);
            $tagName = $matches[$i][1][0];
            $tagParams = $matches[$i][2][0];
            if ($contentStartIndex != $tagStartIndex) {
                $content = substr($string, $contentStartIndex, $tagStartIndex - $contentStartIndex);
                $output[] = $multiline ? nl2br(htmlspecialchars($content)) : htmlspecialchars($content);
            }
            $tagContent = $callback($tagName, $tagParams);
            if ($tagContent !== null) {
                $output[] = $tagContent;
            }
            $contentStartIndex = $tagStartIndex + $tagLength;
        }
        if ($contentStartIndex < strlen($string)) {
            $content = substr($string, $contentStartIndex);
            $output[] = $multiline ? nl2br(htmlspecialchars($content)) : htmlspecialchars($content);
        }
        return implode('', $output);
    }

    /**
     * Strip tags from a tagged text.
     * @param string $string input string
     * @return string result string
     */
    public static function stripTaggedText($string) {
        return preg_replace('/\[[^]]+\]/', '', $string);
    }

}
