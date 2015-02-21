<?php

namespace cotcot\tools;

/**
 * Var utils class.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class VarUtils {

    /**
     * Uncamelize a string.
     * @param string $input input string
     * @return string uncamelized string
     */
    public static function uncamelize($input) {
        return preg_replace_callback('/(^|[a-z])([A-Z])/', function($matches) {
            return strtolower(strlen($matches[1]) ? $matches[1] . '_' . $matches[2] : $matches[2]);
        }, $input);
    }

    /**
     * Camelize a string.
     * @param string $input input string
     * @return string camelized string
     */
    public static function camelize($input) {
        return preg_replace_callback('/(^|_)([a-z])/', function($matches) {
            return strtoupper($matches[2]);
        }, $input);
    }

    /**
     * Build a variable name from an arbitrary string.
     * @param string $input input string
     * @return string camelized variable name
     */
    public static function buildVarName($input) {
        return lcfirst(self::camelize(preg_replace('/^[^a-z_]+[^a-z0-9_-]*$/i', '_', $input)));
    }

}
