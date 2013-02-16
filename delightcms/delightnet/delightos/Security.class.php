<?php

/*
 * security methods, for example strip tags of strings
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   3.11
 * 
 */

namespace delightnet\delightos;

class Security {

    private $whitelist;

    public function __construct() {
        $this->whitelist = "a-zA-Z0-9äöüÄÖÜ§\+\-\!\*\#@ß";
    }

    public static function undoMagicQuotes($strOrArray) {
        if (is_string ($strOrArray)) {
            return stripslashes($strOrArray);
        }
        else {
        $newArray = array();

        foreach ($strOrArray as $key => $value) {
            if (is_array($value)) {
                $newArray[$key] = Security::undoMagicQuotes($value, false);
            } else {
                $newArray[$key] = stripslashes($value);
            }
        }
            return $newArray;
        }
    }
    
    public static function undoTags($strOrArray) {
        if (is_string ($strOrArray)) {
            return strip_tags($strOrArray);
        }
        else {
        $newArray = array();

        foreach ($strOrArray as $key => $value) {
            if (is_array($value)) {
                $newArray[$key] = Security::undoTags($value, false);
            } else {
                $newArray[$key] = strip_tags($value);
            }
        }
            return $newArray;
        }
    }

    public function checkOfWhitelist($strLiteral) {
        $isWhiteList = true;

        if (!preg_match("#^[a-zA-Z0-9" . $this->whitelist . "]+$#", $strLiteral)) {
            $isWhiteList = false;
        }

        return $isWhiteList;
    }

    public function testLimitStrLength($testlength, $strData) {

        $data = ( strlen($strData) > $testlength ) ? null : $data;

        return $data;
    }

}