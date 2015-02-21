<?php

namespace cotcot\tools;

/**
 * String utils.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class StringUtils {

    /**
     * Build a slug string.
     * Ex : "I am the best" => "i-am-the-best".
     * @param string $string input string
     * @param string $separator separator
     * @param int $maxLength max output length
     * @return string filename part
     */
    public static function toSlug($string, $separator = '-', $maxLength = 128) {
        return substr(urlencode(strtolower(trim(preg_replace('/[ ' . $separator . ']+/', $separator, preg_replace('/[^a-zA-Z0-9]/', $separator, self::removeAccents(trim($string)))), '-'))), 0, $maxLength);
    }

    /**
     * Build a random key.
     * @param int $count output char count
     * @param int $seed seed (if null, microtime function is used)
     * @return string key
     */
    public static function buildRandomKey($count, $seed = null) {
        mt_srand(intval($seed !== null ? $seed : microtime(true) * 100));
        $charMin = ord('a');
        $charMax = ord('z');
        $intMin = ord('0');
        $intMax = ord('9');
        $result = '';
        for ($i = 0; $i < $count; $i++) {
            $result.=chr(mt_rand(0, 2) == 0 ? mt_rand($charMin, $charMax) : mt_rand($intMin, $intMax));
        }
        return $result;
    }

    /**
     * Build a human readable string from a data size.
     * @param int $size data size
     * @param int $scale scale
     * @param string $unit unit
     * @param string $kilo kilo unit
     * @param string $mega mega unit
     * @param string $giga giga unit
     * @param string $tera tera unit
     * @return string result string
     */
    public static function toDataSizeString($size, $scale = 1024, $unit = 'oct', $kilo = 'Ko', $mega = 'Mo', $giga = 'Go', $tera = 'To') {
        if ($size < $scale) {
            return $size . ' ' . $unit;
        } elseif ($size < ($scale * $scale)) {
            return sprintf('%01.2f ' . $kilo, $size / $scale);
        } elseif ($size < ($scale * $scale * $scale)) {
            return sprintf('%01.2f ' . $mega, $size / ($scale * $scale));
        } elseif ($size < ($scale * $scale * $scale * $scale)) {
            return sprintf('%01.2f ' . $giga, $size / ($scale * $scale * $scale));
        }
        return sprintf('%01.2f ' . $tera, $size / ($scale * $scale * $scale * $scale));
    }

    /**
     * String cut.
     * @param string $string input string
     * @param int $maxLength max length
     * @param string $suffix suffix
     * @param boolean $isWordSafe word safe flag
     * @return string result string
     */
    public static function cut($string, $maxLength, $suffix = '', $isWordSafe = false) {
        $string = rtrim($string);
        if (mb_strlen($string) > $maxLength) {
            $suffixLength = mb_strlen($suffix);
            if ($suffixLength >= $maxLength) {
                $suffix = '';
                $suffixLength = 0;
            }
            $maxLength-=$suffixLength;
            if ($isWordSafe) {
                $spaces = array(' ', PHP_EOL, '.', ',', ';', ':', '!', '?', '…', '"', ')', ']', '}');
                if (in_array($string[$maxLength], $spaces) == false) {
                    $lastSpacePos = mb_strrpos(mb_substr($string, 0, $maxLength), ' ');
                    if ($lastSpacePos !== false) {
                        $maxLength = $lastSpacePos;
                    }
                }
            }
            return mb_substr($string, 0, $maxLength) . $suffix;
        }
        return $string;
    }

    /**
     * Remove accents from a string.
     * @param string $string input string
     * @return string result string
     */
    public static function removeAccents($string) {
        $result = '';
        $length = mb_strlen($string);
        for ($i = 0; $i < $length; $i++) {
            switch ($char = mb_substr($string, $i, 1)) {
                case 'ä':
                case 'à':
                case 'â':$result.='a';
                    break;
                case 'é':
                case 'è':
                case 'ê':
                case 'ë':$result.='e';
                    break;
                case 'î':
                case 'ï':$result.='i';
                    break;
                case 'ô':
                case 'œ':
                case 'ö':$result.='o';
                    break;
                case 'ü':
                case 'ù':
                case 'û':$result.='u';
                    break;
                case 'ÿ':$result.='y';
                    break;
                case 'Ä':
                case 'À':
                case 'Â':$result.='A';
                    break;
                case 'É':
                case 'È':
                case 'Ê':
                case 'Ë':$result.='E';
                    break;
                case 'Î':
                case 'Ï':$result.='I';
                    break;
                case 'Ô':
                case 'Œ':
                case 'Ö':$result.='O';
                    break;
                case 'Ü':
                case 'Ù':
                case 'Û':$result.='U';
                    break;
                case 'ç':$result.='c';
                    break;
                case 'Ç':$result.='C';
                    break;
                case 'Ÿ':$result.='Y';
                    break;
                case 'æ':$result.='a';
                    break;
                case 'Æ':$result.='a';
                    break;
                default:$result.=$char;
            }
        }
        return $result;
    }

    /**
     * Pluralize a string.
     * @param int $count count
     * @param string $singular singular string
     * @param string $plural plural string
     * @return string result string
     */
    public static function pluralize($count, $singular, $plural = null) {
        if ($plural === null) {
            $plural = $singular . 's';
        }
        return $count > 1 ? $plural : $singular;
    }

    /**
     * Implode an array into a string.
     * Empty elements are removed.
     * @param string $glue glue
     * @param array $pieces input array. 
     */
    public static function implodePack($glue, $pieces) {
        return implode($glue, array_filter($pieces, function($value) {
                    return !empty($value);
                }));
    }

}
