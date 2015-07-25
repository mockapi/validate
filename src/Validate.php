<?php

namespace Mockapi\Validate;

use \Exception;
use \Inflect\Inflect;

class Validate
{
    static $httpStatusCodes = [100, 101, 102, 200, 201, 202, 203, 204, 205, 206, 207, 208, 226, 300, 301, 302, 303, 304, 305, 306, 307, 308, 400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 422, 423, 424, 425, 426, 428, 429, 431, 500, 501, 502, 503, 504, 505, 506, 507, 508, 510, 511];

    /**
     * Common helper function to either throw Exception or return false
     *
     * Last argument of validators $throwWithName is mixed type:
     * boolean `false` or name of the variable to show (string).
     *
     * Passing flase to $throwWithName causes validator to return
     * simple `false` on validation errors and `true` on success.
     *
     * `true` is returned on default by every validator method.
     *
     */
    private static function throwMustBe($mustbe, $name)
    {
        if ($name) {
            if (is_string($name)) {
                throw new Exception("`{$name}` must be {$mustbe}.");
            }

            throw new Exception("Value must be {$mustbe}.");
        }

        return false;
    }

    //////

    public static function isWritableDir($v, $throwWithName = true)
    {
        if (realpath($v) && is_dir($v) && is_writable($v)) {
            return true;
        }

        self::throwMustbe('a writable path to a dir', $throwWithName);
    }

    public static function requireAttributes(array $require, array $a, $throwWithName = true)
    {
        foreach ($require as $attr) {
            if (!isset($a[$attr])) {
                self::throwMustbe("define `{$attr}` attribute", $throwWithName);
            }
        }

        return true;
    }

    public static function isWritableFile($v, $throwWithName = true)
    {
        if (realpath($v) && is_file($v) && is_writable($v)) {
            return true;
        }

        self::throwMustbe('a writable file', $throwWithName);
    }

    public static function isNonEmptyString($v, $throwWithName = true)
    {
        if (is_string($v) && strlen($v) > 0) {
            return true;
        }

        self::throwMustbe('a non-empty string', $throwWithName);
    }

    public static function isUri($v, $throwWithName = true)
    {
        self::isNonEmptyString($v, $throwWithName);

        if (preg_match('|^[/\w\d-_%]+$|', $v, $m)) {
            return true;
        }

        self::throwMustbe('a URI string', $throwWithName);
    }

    public static function isUrl($v, $throwWithName = true)
    {
        self::isNonEmptyString($v, $throwWithName);

        if (preg_match('|^[:/\w\d-_%]+$|', $v, $m)) {
            return true;
        }

        self::throwMustbe('a URI string', $throwWithName);
    }

    public static function isStatusCode($v, $throwWithName = true)
    {
        if (is_int($v) && in_array($v, self::$httpStatusCodes)) {
            return true;
        }

        self::throwMustbe('a valid HTTP/1.1 status code', $throwWithName);
    }

    public static function isPlural($w, $throwWithName = true)
    {
        if ($w === Inflect::pluralize($w)) {
            return true;
        }

        self::throwMustBe('plural', $throwWithName);
    }

    public static function isSingular($w, $throwWithName = true)
    {
        if ($w === Inflect::singularize($w)) {
            return true;
        }

        self::throwMustBe('singular', $throwWithName);
    }

    public static function isObject($o, $throwWithName = true)
    {
        if (is_object($o)) {
            return true;
        }

        self::throwMustBe('object', $throwWithName);
    }
}
